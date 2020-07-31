<template>
  <div>
      <div class="card">
        <div class="card-header">
            <span @click="back">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-left-short" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M7.854 4.646a.5.5 0 0 1 0 .708L5.207 8l2.647 2.646a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 0 1 .708 0z"/>
                    <path fill-rule="evenodd" d="M4.5 8a.5.5 0 0 1 .5-.5h6.5a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5z"/>
                </svg>
            </span>
            <span class="title">Detalhes do evento</span>
        </div>
        <div class="card-body">
            <div class="card-title">
                <span class="name">{{ event.name }}</span>

                <template v-if="event.canceled || canceled">
                    - <span class="canceled">Cancelado</span>
                </template>

            </div>
            <h6 class="card-subtitle mb-2">Data: {{ event.date_event | formatDate }}</h6>

            <div>
                <span class="card-text">Evento criado por: {{ event.owner_user.name }}</span>
            </div>

            <div class="description">
                <span>{{ event.description }}</span>
            </div>

            <div class="actions">
                <div class="margin-right" v-if="!event.canceled && !event.userLoggedIsOwnerEvent && !event.userLoggedConfirmed">
                    <a href="#" class="card-link" @click="requestParticipation">Participar</a>
                </div>

                <template v-if="event.userLoggedIsOwnerEvent && !event.canceled && !canceled">
                    <div class="margin-right">
                        <a id="create_form_icon" href="#" class="card-link" @click="openModal">Convidar usuário</a>
                    </div>

                    <div>
                        <a href="#" class="card-link" @click="goToEditEevent">Editar evento</a>
                    </div>
                </template>
            </div>

            <template v-if="event.userLoggedIsOwnerEvent && !event.canceled && !canceled">
                <div class="button-cancel">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button class="btn btn-primary btn-sm" type="submit" @click="openModalNotification">Notificar convidados</button>
                        <button class="btn btn-danger btn-sm" type="submit" @click="cancelEvent">Cancelar Evento</button>
                    </div>
                </div>
            </template>

            <div class="button-cancel" v-if="!event.canceled && event.userLoggedConfirmed && !event.userLoggedIsOwnerEvent">
                <button class="btn btn-danger btn-sm" type="submit" @click="cancelParticipation">Cancelar participação</button>
            </div>

            <guests-component :event="event"></guests-component>
        </div>
    </div>

    <schedule-notifications-component ref="form" :event="event"></schedule-notifications-component>

    <div class="modal fade" id="invite-modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Convidar usuário</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                     <form @submit.prevent="sendInvite">
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">E-mail usuário: </label>

                            <div class="col-md-6">
                                <input v-model="email" type="email" name="email" class="form-control" autofocus>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button
                                :disabled="disabled" type="submit" class="btn btn-success">
                                Convidar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
    props: ['event'],
    data(){
        return {
            email: '',
            canceled: false,
        }
    },
    created(){
    },
    computed: {
        disabled(){
            return  this.email === '';
        },
    },
    methods: {
        openModalNotification(){
           this.$refs.form.openModal();
        },
        openModal(){
            $('#invite-modal').modal('show');
        },
        requestParticipation(){
            axios.post(`/guests/event/${this.$props.event.id}/request-participation`).then((resp) => {
                window.location.reload(`${window.origin}/event/${this.$props.event.id}`);
            }).catch(error => {
                parseError(error.response.data['msg'] ? error.response.data['msg'] : error);
            });
        },
        cancelParticipation(){
            axios.post(`/guests/event/${this.$props.event.id}/cancel-participation`).then((resp) => {
                window.location.reload(`${window.origin}/event/${this.$props.event.id}`);
            }).catch(error => {
                parseError(error.response.data['msg'] ? error.response.data['msg'] : error);
            });
        },
        cancelEvent(){
            axios.post(`/events/${this.$props.event.id}/cancel`).then((resp) => {
                this.canceled = true;
                parseSuccess(resp.data.msg);
            }).catch(error => {
                parseError(error.response.data['msg'] ? error.response.data['msg'] : error);
            });
        },
        sendInvite () {
            axios.post(`/events/${this.$props.event.id}/send-invite`, {email: this.email}).then((resp) => {
                parseSuccess(resp.data.msg);
                $('#invite-modal').modal('hide');
            }).catch(error => {
                parseError(error.response.data['msg'] ? error.response.data['msg'] : error);
                $('#invite-modal').modal('hide');
            });
        },
        goToEditEevent(){
            window.location.href = `${window.origin}/events/${this.$props.event.id}/edit`;
        },
        back() {
            window.location.href = window.origin;
        },
    }
}
function parseSuccess(msg){
    $("#alert").removeClass("alert-danger");
    $("#alert").addClass("alert-success");
    $("#msg").text(msg);
    $('#alert').show();
}

function parseError(msg){
    $("#alert").removeClass("alert-success");
    $("#alert").addClass("alert-danger");
    $("#msg").text(msg);
    $('#alert').show();
}
</script>

<style scoped>
</style>
