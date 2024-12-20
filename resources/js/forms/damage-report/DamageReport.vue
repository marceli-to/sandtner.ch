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
  <form @submit.prevent="submitForm" class="space-y-10 lg:space-y-20 mt-30">
    <div class="flex flex-col space-y-20 md:space-y-0 md:gap-y-45 md:grid md:grid-cols-6 sm:gap-15 md:gap-30">
      <div class="sm:col-span-2">
        <h3 class="font-extrabold uppercase mb-10 lg:mb-15">Persönliche Daten</h3>
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
          <form-group>
            <form-textarea-field 
              v-model="form.message" 
              :error="errors.message"
              @update:error="errors.message = $event"
              class="md:min-h-160"
              placeholder="Ihre Nachricht"
            />
          </form-group>
        </div>
      </div>
      <div class="sm:col-span-2">
        <h3 class="font-extrabold uppercase mb-10 lg:mb-15">Schaden</h3>
        <div class="flex flex-col space-y-25">
          <form-group>
            <form-textarea-field 
              v-model="form.damage_description" 
              :error="errors.damage_description"
              @update:error="errors.damage_description = $event"
              class="md:min-h-200"
              placeholder="Art des Schadens"
            />
          </form-group>
          <form-group>
            <form-text-field 
              v-model="form.vehicle" 
              :error="errors.vehicle"
              @update:error="errors.vehicle = $event"
              placeholder="Fahrzeugmarke, Fahrzeugtyp *"
            />
          </form-group>
          <form-group>
            <form-text-field 
              v-model="form.control_plate" 
              :error="errors.control_plate"
              @update:error="errors.control_plate = $event"
              placeholder="Kontrollschilder *"
            />
          </form-group>
          <form-group>
            <form-text-field 
              v-model="form.insurance_company" 
              :error="errors.insurance_company"
              @update:error="errors.insurance_company = $event"
              placeholder="Versicherungsgesellschaft *"
            />
          </form-group>

          <div>
            <h3 class="font-extrabold uppercase mb-10 lg:mb-15">Wie sind Sie versichert?</h3>
            <div class="flex flex-col space-y-8 relative">
              <Error :error="errors.insurance_type" />
              <form-radio-field 
                id="insurance_type_full"
                name="insurance_type"
                v-model="form.insurance_type" 
                label="Vollkasko"
                value="Vollkasko" />

              <form-radio-field 
                id="insurance_type_partial"
                name="insurance_type"
                v-model="form.insurance_type" 
                label="Teilkasko"
                value="Teilkasko" />

              <form-radio-field 
                id="insurance_type_liability"
                name="insurance_type"
                v-model="form.insurance_type" 
                label="Haftpflicht"
                value="Haftpflicht" />
            </div>
          </div>

          <div>
            <h3 class="font-extrabold uppercase mb-10 lg:mb-15">Fotos</h3>
            <div class="flex flex-col space-y-25">
              <form-group :classes="'relative'">
                <label class="font-bold block mb-5">Fahrzeugausweis</label>
                <Error :error="errors.images_registration_card" />
                <form-file-field
                  accepted-file-types=".jpg,.jpeg,.png"
                  :max-file-size="2 * 1024 * 1024"
                  :allow-multiple="true"
                  identifier="images_registration_card"
                  @update:files="handleFilesUpdate"
                />
              </form-group>

              <form-group>
                <label class="font-bold block mb-5">Kilometerstand</label>
                <Error :error="errors.images_mileage" />
                <form-file-field
                  accepted-file-types=".jpg,.jpeg,.png"
                  :max-file-size="2 * 1024 * 1024"
                  :allow-multiple="true"
                  identifier="images_mileage"
                  @update:files="handleFilesUpdate"
                />
              </form-group>

              <form-group>
                <label class="font-bold block mb-5">Gesamtes Fahrzeug</label>
                <Error :error="errors.images_vehicle" />
                <form-file-field
                  accepted-file-types=".jpg,.jpeg,.png"
                  :max-file-size="2 * 1024 * 1024"
                  :allow-multiple="true"
                  identifier="images_vehicle"
                  @update:files="handleFilesUpdate"
                />
              </form-group>

              <form-group>
                <label class="font-bold block mb-5">Detailaufnahme</label>
                <Error :error="errors.images_damage" />
                <form-file-field
                  accepted-file-types=".jpg,.jpeg,.png"
                  :max-file-size="2 * 1024 * 1024"
                  :allow-multiple="true"
                  identifier="images_damage"
                  @update:files="handleFilesUpdate"
                />
              </form-group>
            </div>
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
      <div class="sm:col-span-2">

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
import FormFileField from '@/forms/components/fields/file.vue';
import Error from '@/forms/components/fields/error.vue';
import SuccessAlert from '@/forms/components/alerts/success.vue';
import ErrorAlert from '@/forms/components/alerts/error.vue';

const isSubmitting = ref(false);
const formSuccess = ref(false);
const formError = ref(false);

const form = ref({
  firstname: '',
  name: '',
  email: '',
  phone: '',
  message: '',
  damage_description: '',
  vehicle: '',
  control_plate: '',
  insurance_company: '',
  insurance_type: '',
  images_registration_card: [],
  images_mileage: [],
  images_vehicle: [],
  images_damage: []
});

const errors = ref({
  firstname: '',
  name: '',
  email: '',
  phone: '',
  message: '',
  damage_description: '',
  vehicle: '',
  control_plate: '',
  insurance_company: '',
  insurance_type: '',
  images_registration_card: '',
  images_mileage: '',
  images_vehicle: '',
  images_damage: ''
});

async function submitForm() {
  isSubmitting.value = true;
  formSuccess.value = false;
  formError.value = false;
  
  try {
    const formData = new FormData();
    
    // Add all regular form fields
    Object.keys(form.value).forEach(key => {
      if (key !== 'images_registration_card' && key !== 'images_mileage' && key !== 'images_vehicle' && key !== 'images_damage') {
        formData.append(key, form.value[key]);
      }
    });

    // Add files separately
    if (form.value.images_registration_card?.length) {
      form.value.images_registration_card.forEach((file, index) => {
        formData.append(`images_registration_card[${index}]`, file);
      });
    }

    if (form.value.images_mileage?.length) {
      form.value.images_mileage.forEach((file, index) => {
        formData.append(`images_mileage[${index}]`, file);
      });
    }

    if (form.value.images_vehicle?.length) {
      form.value.images_vehicle.forEach((file, index) => {
        formData.append(`images_vehicle[${index}]`, file);
      });
    }

    if (form.value.images_damage?.length) {
      form.value.images_damage.forEach((file, index) => {
        formData.append(`images_damage[${index}]`, file);
      });
    }

    // Make the API request
    const response = await axios.post('/api/damage-report/submission', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    handleSuccess();
  }
  catch (error) {
    handleError(error);
  }
  finally {
    isSubmitting.value = false;
  }
}

const resetForm = () => {
  Object.keys(form.value).forEach(key => {
    if (key.startsWith('images_')) {
      form.value[key] = [] // Set empty array for image fields
    } else {
      form.value[key] = '' // Set empty string for other fields
    }
  })
  Object.keys(errors.value).forEach(key => {
    errors.value[key] = '';
  });
};

function handleSuccess() {
  formSuccess.value = true;
  formError.value = false;
  resetForm();
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
}

function handleError(error) {
  isSubmitting.value = false;
  formError.value = true;

  if (error.response && error.response.data && typeof error.response.data.errors === 'object') {
    Object.keys(error.response.data.errors).forEach(key => {
      errors.value[key] = error.response.data.errors[key];
    });
  }
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
}

const handleFilesUpdate = ({ files, identifier }) => {
  switch(identifier) {
    case 'images_registration_card':
      form.value.images_registration_card = files;
    break;
    case 'images_mileage':
      form.value.images_mileage = files;
    break;
    case 'images_vehicle':
      form.value.images_vehicle = files;
    break;
    case 'images_damage':
      form.value.images_damage = files;
    break;
  }
}
</script>