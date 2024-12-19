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
    {{  form.date }}
    <div class="flex flex-col space-y-20 sm:space-y-0 sm:gap-y-30 md:gap-y-45 sm:grid sm:grid-cols-6 sm:gap-15 md:gap-30">
      <div class="sm:col-span-2">
        <h3 class="font-extrabold uppercase mb-10 lg:mb-15">1. Terminauswahl</h3>
        <form-group>
          <form-date-picker v-model="form.date" />
        </form-group>
      </div>
        <!--
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
            v-model="form.subject" 
            :error="errors.subject"
            @update:error="errors.subject = $event"
            placeholder="Betreff *"
          />
        </form-group>
        <form-group>
          <form-textarea-field 
            v-model="form.message" 
            :error="errors.message"
            @update:error="errors.message = $event"
            placeholder="Ihre Nachricht *"
          />
        </form-group>
        -->
        <!-- <form-group class="!mt-35">
          <form-button 
            type="submit" 
            :label="'Terminanfrage senden'"
            :disabled="isSubmitting"
            :submitting="isSubmitting"
          />
        </form-group> -->
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
import Error from '@/forms/components/fields/error.vue';
import SuccessAlert from '@/forms/components/alerts/success.vue';
import ErrorAlert from '@/forms/components/alerts/error.vue';

const isSubmitting = ref(false);
const formSuccess = ref(false);
const formError = ref(false);

const form = ref({
  date: ref(new Date()),
  time: null,
  name: null,
  firstname: null,
  email: null,
  subject: null,
  message: null
});

const errors = ref({
  date: '',
  time: '',
  name: '',
  firstname: '',
  email: '',
  subject: '',
  message: '',
});

async function submitForm() {
  isSubmitting.value = true;
  formSuccess.value = false;
  formError.value = false;
  try {
    const response = await axios.post('/api/contact/submission', {
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
    name: null,
    firstname: null,
    email: null,
    subject: null,
    message: null
  };
  
  errors.value = {
    date: '',
    time: '',
    name: '',
    firstname: '',
    email: '',
    subject: '',
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