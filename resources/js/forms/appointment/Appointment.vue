<template>
  <template v-if="formSuccess">
    <success-alert>
      Vielen Dank für Ihre Anfrage!
    </success-alert>
  </template>
  <template v-if="formError">
    <error-alert>
      Bitte überprüfen Sie die eingegebenen Daten.
    </error-alert>
  </template>
  <form @submit.prevent="submitForm" class="space-y-10 lg:space-y-20">
    <div class="flex flex-col space-y-20 md:space-y-0 md:gap-y-45 md:grid md:grid-cols-6 sm:gap-15 md:gap-30">
      <div class="sm:col-span-2">
        <h3 class="font-extrabold uppercase mb-10 lg:mb-15">1. Terminauswahl</h3>
        <div class="flex md:flex-col gap-x-15 md:space-y-25">
          <form-group>
            <form-date-picker v-model="form.date" />
          </form-group>
          <form-group class="w-1/2 md:w-auto lg:w-auto">
            <form-time-field v-model="form.time" />
          </form-group>
        </div>
      </div>
      <div class="sm:col-span-2">
        <h3 class="font-extrabold uppercase mb-10 lg:mb-15">2. Grund des Termins</h3>
        <div class="flex flex-col space-y-25">
          <form-group>
           <form-select-field v-model="form.reason" :options="reasons" />
          </form-group>
          <form-group>
            <form-text-field v-model="form.control_plate" placeholder="Kontrollschild" />
          </form-group>
          <form-group>
            <form-textarea-field 
              v-model="form.message" 
              :error="errors.message"
              @update:error="errors.message = $event"
              class="md:min-h-[256px] lg:min-h-[271px]"
              placeholder="Ihre Nachricht"
            />
          </form-group>
        </div>
      </div>
      <div class="sm:col-span-2">
        <h3 class="font-extrabold uppercase mb-10 lg:mb-15">3. Kontaktdaten</h3>
        <div class="flex flex-col space-y-25">
          <form-group>
            <form-text-field 
              v-model="form.firstname" 
              :error="errors.firstname"
              @update:error="errors.firstname = $event"
              placeholder="Vorname *"
            />
          </form-group>
          <form-group>
            <form-text-field 
              v-model="form.name" 
              :error="errors.name"
              @update:error="errors.name = $event"
              placeholder="Name *"
            />
          </form-group>
          <form-group>
            <form-text-field 
              type="email"
              v-model="form.email" 
              :error="errors.email"
              @update:error="errors.email = $event"
              placeholder="E-Mail *"
            />
          </form-group>
          <form-group>
            <form-text-field 
              type="text"
              v-model="form.phone" 
              :error="errors.phone"
              @update:error="errors.phone = $event"
              placeholder="Telefon *"
            />
          </form-group>
          <div>
            <p>Damit wir Ihre Terminanfrage bestätigen können, müssen Sie alle Felder ausfüllen. Nachdem wir Ihre Anfrage im System bearbeitet haben, erhalten Sie per E-Mail eine Bestätigung.</p>
          </div>
          <form-group :classes="'lg:!mt-43'">
            <form-button 
              type="submit" 
              :label="'Terminanfrage senden'"
              :disabled="isSubmitting"
              :submitting="isSubmitting"
            />
          </form-group>
        </div>
      </div>
    </div>
  </form>
</template>
<script setup>
import { ref, watch } from 'vue';
import axios from 'axios';
import FormGroup from '@/forms/components/fields/group.vue';
import FormTextField from '@/forms/components/fields/text.vue';
import FormTextareaField from '@/forms/components/fields/textarea.vue';
import FormLabel from '@/forms/components/fields/label.vue';
import FormButton from '@/forms/components/fields/button.vue';
import FormSelectField from '@/forms/components/fields/select.vue';
import FormRadioField from '@/forms/components/fields/radio.vue';
import FormDatePicker from '@/forms/components/fields/datepicker.vue';
import FormTimeField from '@/forms/components/fields/time.vue';
import Error from '@/forms/components/fields/error.vue';
import SuccessAlert from '@/forms/components/alerts/success.vue';
import ErrorAlert from '@/forms/components/alerts/error.vue';

const isSubmitting = ref(false);
const formSuccess = ref(false);
const formError = ref(false);

const reasons = ref([
  { value: 'Termin', label: 'Termin' },
  { value: 'Bewerbung', label: 'Bewerbung' },
  { value: 'Kontakt', label: 'Kontakt' },
  { value: 'Ausbildung', label: 'Ausbildung' },
  { value: 'Sonstiges', label: 'Sonstiges' },
]);

const form = ref({
  date: new Date(),
  time: null,
  reason: 'Termin',
  control_plate: null,
  name: null,
  firstname: null,
  email: null,
  subject: null,
  message: null
});

const errors = ref({
  date: '',
  time: '',
  reason: '',
  control_plate: '',
  name: '',
  firstname: '',
  email: '',
  message: '',
});

async function submitForm() {
  isSubmitting.value = true;
  formSuccess.value = false;
  formError.value = false;
  console.log(form.value);
  try {
    const response = await axios.post('/api/appointment/request', {
      ...form.value
    });
    handleSuccess();
  } catch (error) {
    handleError(error);
  }
}

function handleSuccess() {
  form.value = {
    date: null,
    time: null,
    reason: null,
    control_plate: null,
    name: null,
    firstname: null,
    email: null,
    message: null
  };
  
  errors.value = {
    date: '',
    time: '',
    reason: '',
    control_plate: '',
    name: '',
    firstname: '',
    email: '',
    message: '',
  };
  
  isSubmitting.value = false;
  formSuccess.value = true;
}

function handleError(error) {
  isSubmitting.value = false;
  formError.value = true;

  if (error.response && error.response.data && typeof error.response.data.errors === 'object') {
    Object.keys(error.response.data.errors).forEach(key => {
      errors.value[key] = error.response.data.errors[key];
    });
  }
}
</script>