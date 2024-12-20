<template>
  <div>
    <!-- File Upload Button -->
    <div class="bg-white border border-graphite">
      <label class="block py-5 pl-15 pr-5 cursor-pointer" :class="{ 'cursor-not-allowed opacity-50': disabled }">
        <input
          type="file"
          class="hidden"
          :accept="acceptedFileTypes"
          :multiple="allowMultiple"
          @change="handleFileUpload"
          :disabled="disabled"
        >
        <div>
          Bild auswählen...
        </div>
      </label>
    </div>

    <div class="text-xs mt-5">
      Dateityp {{ acceptedFileTypes.replace(/\./g, '') }}, max. {{ formatFileSize(maxFileSize) }}
    </div>

    <!-- Error Messages -->
    <div v-if="error" class="mt-5 text-sm text-red-600">
      {{ error }}
    </div>

    <!-- File List -->
    <div v-if="files.length > 0" class="mt-10 flex flex-col space-y-10">
      <div v-for="(file, index) in files" :key="index" class="flex justify-between bg-silver p-5">
        <div class="text-sm">
          {{ truncateFileName(file.name) }}
        </div>
        <button 
          @click="removeFile(index)"
          class="text-graphite hover:text-tangerine"
        >
          <svg 
            class="size-16" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
          >
            <path 
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="2" 
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
            />
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  acceptedFileTypes: {
    type: String,
    default: '.jpg,.jpeg,.png'
  },
  maxFileSize: {
    type: Number,
    default: 2 * 1024 * 1024 // 2MB in bytes
  },
  allowMultiple: {
    type: Boolean,
    default: false
  },
  disabled: {
    type: Boolean,
    default: false
  },
  identifier: {
    type: String,
    required: true
  }
})

const emit = defineEmits(['update:files'])

const files = ref([]);
const error = ref('');

// Handle file upload
const handleFileUpload = (event) => {
  const uploadedFiles = Array.from(event.target.files)
  error.value = ''

  // Validate each file
  uploadedFiles.forEach(file => {
    // Check file size
    if (file.size > props.maxFileSize) {
      error.value = `File ${file.name} exceeds maximum size of ${formatFileSize(props.maxFileSize)}`
      return
    }

    // Check file type
    const fileExtension = `.${file.name.split('.').pop().toLowerCase()}`
    if (!props.acceptedFileTypes.includes(fileExtension)) {
      error.value = `File ${file.name} has invalid type. Accepted types: ${props.acceptedFileTypes}`
      return
    }

    // If multiple files are not allowed, replace existing files
    if (!props.allowMultiple) {
      files.value = []
    }

    files.value.push(file)
  })

  // Clear input value to allow uploading the same file again
  event.target.value = ''
  
  // Emit updated files
  emit('update:files', { files: files.value, identifier: props.identifier })
}

// Remove file from list
const removeFile = (index) => {
  files.value.splice(index, 1)
  emit('update:files', files.value)
}

// Truncate filename if it's too long
const truncateFileName = (fileName) => {
  if (fileName.length <= 40) return fileName
  
  const extension = fileName.split('.').pop()
  const nameWithoutExt = fileName.slice(0, fileName.lastIndexOf('.'))
  const maxLength = 47 - extension.length // 22 to account for '...' and the dot
  
  return `${nameWithoutExt.slice(0, maxLength)}...${extension}`
}


// Format file size to human readable format
const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return `${parseFloat((bytes / Math.pow(k, i)).toFixed(2))} ${sizes[i]}`
}
</script>