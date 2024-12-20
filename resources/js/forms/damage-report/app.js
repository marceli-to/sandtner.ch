import { createApp } from 'vue';
import DamageReport from './DamageReport.vue';
const app = createApp({});
app.component('damage-report-form', DamageReport);
if (document.getElementById('damage-report-form')) {
  app.mount('#damage-report-form');
}
