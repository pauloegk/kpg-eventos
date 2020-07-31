<template>
  <div class="guests">
    <h5>Convidados confirmados:</h5>
    <span v-if="!guests.length"> Nenhum usuário confirmou sua presença.</span>
    <ul>
        <li v-for="guest in guests" v-bind:key="guest.id">
            {{guest.user.name}}
        </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios'
export default {
    props: ['event'],
    data(){
        return {
            guests: [],
        }
    },
    created(){
        this.getGuests();
    },
  methods: {
    getGuests(){
        axios.get(`/events/${this.$props.event.id}/guests`).then(res => {
            this.guests = res.data;
        })
    },
  }
}
</script>
<style scoped>
.guests{
    margin-top: 20px;
}
</style>
