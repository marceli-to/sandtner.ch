<template>
  <div class="date-picker bg-graphite py-10 px-30 w-full">
    <div class="header flex justify-between items-center mb-15">
      <div class="text-white font-extrabold uppercase">
        {{ currentMonth }} {{ currentYear }}
      </div>
      <div class="navigation flex gap-12">
        <button @click="previousMonth" type="button" class="text-tangerine hover:text-white text-lg">
          &#8592;
        </button>
        <button @click="nextMonth" type="button" class="text-tangerine hover:text-white text-lg">
          &#8594;
        </button>
      </div>
    </div>

    <div class="calendar">
      <!-- Weekday headers -->
      <div class="grid grid-cols-7 gap-x-12 mb-4">
        <template v-for="day in weekDays" :key="day">
          <div class="w-full aspect-square flex items-center justify-center text-white font-bold">{{ day }}</div>
        </template>
      </div>

      <!-- Calendar days -->
      <div class="grid grid-cols-7 gap-8">
        <template v-for="{ date, isCurrentMonth, isSelected } in calendarDays" :key="date">
          <button
            @click="selectDate(date)"
            :class="[
              'w-full aspect-square flex items-center justify-center',
              isCurrentMonth ? 'text-white' : 'text-gray-600',
              isSelected ? 'bg-tangerine text-white' : 'hover:bg-silver hover:text-graphite'
            ]"
          >
            {{ date.getDate() }}
          </button>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  modelValue: {
    type: Date,
    default: () => new Date()
  }
})

const emit = defineEmits(['update:modelValue'])

const currentDate = ref(new Date(props.modelValue))
const selectedDate = ref(new Date(props.modelValue))

const weekDays = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So']

const currentMonth = computed(() => {
  return currentDate.value.toLocaleString('de-DE', { month: 'long' })
})

const currentYear = computed(() => {
  return currentDate.value.getFullYear()
})

const calendarDays = computed(() => {
  const year = currentDate.value.getFullYear()
  const month = currentDate.value.getMonth()
  
  const firstDayOfMonth = new Date(year, month, 1)
  const lastDayOfMonth = new Date(year, month + 1, 0)
  
  // Adjust to start week on Monday (1) instead of Sunday (0)
  let firstDayOfWeek = firstDayOfMonth.getDay() || 7
  firstDayOfWeek = firstDayOfWeek - 1
  
  const days = []
  
  // Add days from previous month
  for (let i = firstDayOfWeek - 1; i >= 0; i--) {
    const date = new Date(year, month, -i)
    days.push({
      date,
      isCurrentMonth: false,
      isSelected: isSameDay(date, selectedDate.value)
    })
  }
  
  // Add days from current month
  for (let i = 1; i <= lastDayOfMonth.getDate(); i++) {
    const date = new Date(year, month, i)
    days.push({
      date,
      isCurrentMonth: true,
      isSelected: isSameDay(date, selectedDate.value)
    })
  }
  
  // Add days from next month to complete the grid
  const remainingDays = 42 - days.length // 6 rows * 7 days
  for (let i = 1; i <= remainingDays; i++) {
    const date = new Date(year, month + 1, i)
    days.push({
      date,
      isCurrentMonth: false,
      isSelected: isSameDay(date, selectedDate.value)
    })
  }
  
  return days
})

function isSameDay(date1, date2) {
  return date1.getDate() === date2.getDate() &&
         date1.getMonth() === date2.getMonth() &&
         date1.getFullYear() === date2.getFullYear()
}

function selectDate(date) {
  selectedDate.value = date
  emit('update:modelValue', date)
}

function previousMonth() {
  currentDate.value = new Date(
    currentDate.value.getFullYear(),
    currentDate.value.getMonth() - 1,
    1
  )
}

function nextMonth() {
  currentDate.value = new Date(
    currentDate.value.getFullYear(),
    currentDate.value.getMonth() + 1,
    1
  )
}
</script>