<template>
  <template v-if="formSuccess">
    <success-alert>
      Vielen Dank für Ihre Anmeldung!
    </success-alert>
  </template>
  <template v-if="formError">
    <error-alert>
      Bitte überprüfen Sie die eingegebenen Daten.
    </error-alert>
  </template>
  <form @submit.prevent="submitForm" class="space-y-10 lg:space-y-20">
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
    <form-group class="!mt-35">
      <form-button 
        type="submit" 
        :label="'Anfrage senden'"
        :disabled="isSubmitting"
        :submitting="isSubmitting"
      />
    </form-group>
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
import Error from '@/forms/components/fields/error.vue';
import SuccessAlert from '@/forms/components/alerts/success.vue';
import ErrorAlert from '@/forms/components/alerts/error.vue';

const isSubmitting = ref(false);
const formSuccess = ref(false);
const formError = ref(false);

const form = ref({
  name: null,
  firstname: null,
  email: null,
  subject: null,
  message: null
});

const errors = ref({
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
    name: null,
    firstname: null,
    email: null,
    subject: null,
    message: null
  };
  
  errors.value = {
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