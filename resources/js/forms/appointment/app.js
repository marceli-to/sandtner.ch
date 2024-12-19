import { createApp } from 'vue';
import Appointment from './Appointment.vue';
const app = createApp({});
app.component('appointment-form', Appointment);
if (document.getElementById('appointment-form')) {
  app.mount('#appointment-form');
}
