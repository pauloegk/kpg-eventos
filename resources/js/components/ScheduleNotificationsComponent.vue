<template>
  <div>
    <div class="modal fade" id="create_form_modal" tabindex="-1" role="dialog"
        aria-labelledby="addNewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addNewLabel">Notificar convidados</h5>
                    <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="send">
                    <div class="modal-body">
                        <div class="form-group">
                            <label style="margin-bottom: 10px;">Quando?</label>
                            <div class="form-control">
                                <span style="margin-right: 20px;">
                                    <input type="radio" name="sending"
                                    value="now"
                                    v-model="item" checked
                                    >
                                    <label>Agora</label>
                                </span>
                                <span>
                                    <input type="radio" name="sending"
                                    value="later"
                                    v-model="item"
                                    >
                                    <label>Agendar</label>
                                </span>
                            </div>
                        </div>

                        <div class="margin-bottom">
                            <VueCtkDateTimePicker :no-button-now="true" v-model="send_date" format="DD-MM-YYYY"
                                :onlyDate="true" :minDate="minDate" label="Informe a data" formatted="ll" v-if="item === 'later'"/>
                        </div>

                        <div class="form-group">
                            <label>Titulo</label>
                            <input v-model="title" type="text" name="title"
                            placeholder="Titulo" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Mensagem</label>
                            <textarea v-model="body" name="body" id="body"
                            placeholder="Mensagem" class="form-control" rows="5">
                            </textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button :disabled="disabled" type="submit" class="btn  btn-success">
                            {{ item === 'now' ? 'Enviar agora' : 'Agendar envio' }}
                        </button>
                    </div>
                </form>
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

        const date = new Date();

        return {
            minDate: `${date.getFullYear()}-${date.getMonth() + 1}-${date.getDate()}`,
            title: '',
            body: '',
            send_date: '',
            item: 'now'
        }
    },
    created(){
    },
    computed: {
        disabled(){
            return  this.title === '' || !this.title ||
              this.body === '' || !this.body
        },
    },
    methods: {
        dateNow(){
            return new Date();
        },
        openModal(){
            $('#create_form_modal').modal('show');
        },

        send () {
            const sendData = {
                title: this.title,
                body: this.body,
                send_date: this.send_date,
                event_id: this.$props.event.id,
                item: this.item
            }
            axios.post('/notifications', sendData).then((resp) => {
                console.log(resp.data)
                $('#create_form_modal').modal('hide');
                // this.title = '';
                // this.body = '';
                // this.loading = false
            }).catch(error => {
                console.log(error)
            })
        },
    }
}
</script>

<style scoped>
.margin-bottom{
    margin-bottom: 10px;
}
</style>
