<template>
  <div class="date-picker bg-graphite py-10 pt-5 px-20 w-full">
    <div class="header flex justify-between items-center mb-5">
      <div class="text-white font-extrabold uppercase">
        {{ currentMonth }} {{ currentYear }}
      </div>
      <div class="navigation flex gap-12">
        <button @click="previousMonth" type="button" :disabled="isPreviousMonthDisabled" 
          :class="[
            'text-lg',
            isPreviousMonthDisabled ? 'text-gray-600 cursor-not-allowed' : 'text-tangerine hover:text-white'
          ]">
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
        <template v-for="{ date, isCurrentMonth, isSelected, isPast } in calendarDays" :key="date">
          <button
            @click="selectDate(date)"
            :disabled="isPast"
            :class="[
              'w-full aspect-[16/10] flex items-center justify-center pb-2',
              isCurrentMonth ? 'text-white' : 'text-gray-600',
              isPast ? 'opacity-50 cursor-not-allowed' : '',
              !isPast && isSelected ? 'bg-tangerine text-white' : '',
              !isPast && !isSelected ? 'hover:bg-silver hover:text-graphite' : ''
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
const today = new Date()
today.setHours(0, 0, 0, 0)

const weekDays = ['Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So']

const currentMonth = computed(() => {
  return currentDate.value.toLocaleString('de-DE', { month: 'long' })
})

const currentYear = computed(() => {
  return currentDate.value.getFullYear()
})

const isPreviousMonthDisabled = computed(() => {
  const firstOfCurrentMonth = new Date(
    currentDate.value.getFullYear(),
    currentDate.value.getMonth(),
    1
  )
  return firstOfCurrentMonth <= today
})

function isPastDate(date) {
  return date < today
}

const calendarDays = computed(() => {
  const year = currentDate.value.getFullYear()
  const month = currentDate.value.getMonth()
  
  const firstDayOfMonth = new Date(year, month, 1)
  const lastDayOfMonth = new Date(year, month + 1, 0)
  
  let firstDayOfWeek = firstDayOfMonth.getDay() || 7
  firstDayOfWeek = firstDayOfWeek - 1
  
  const days = []
  
  // Add days from previous month
  for (let i = firstDayOfWeek - 1; i >= 0; i--) {
    const date = new Date(year, month, -i)
    days.push({
      date,
      isCurrentMonth: false,
      isSelected: isSameDay(date, selectedDate.value),
      isPast: isPastDate(date)
    })
  }
  
  // Add days from current month
  for (let i = 1; i <= lastDayOfMonth.getDate(); i++) {
    const date = new Date(year, month, i)
    days.push({
      date,
      isCurrentMonth: true,
      isSelected: isSameDay(date, selectedDate.value),
      isPast: isPastDate(date)
    })
  }
  
  // Add days from next month
  const remainingDays = 42 - days.length
  for (let i = 1; i <= remainingDays; i++) {
    const date = new Date(year, month + 1, i)
    days.push({
      date,
      isCurrentMonth: false,
      isSelected: isSameDay(date, selectedDate.value),
      isPast: isPastDate(date)
    })
  }
  
  return days
})

function isSameDay(date1, date2) {
  return date1.getDate() === date2.getDate() &&
         date1.getMonth() === date2.getMonth() &&
         date1.getFullYear() === date2.getFullYear()
}

function toLocalISOString(date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  return `${year}-${month}-${day}`;
}

function createDate(year, month, day) {
  const date = new Date(year, month, day);
  const userTimezoneOffset = date.getTimezoneOffset() * 60000;
  return new Date(date.getTime() - userTimezoneOffset);
}

function selectDate(date) {
  if (isPastDate(date)) return;
  
  const localDate = createDate(
    date.getFullYear(),
    date.getMonth(),
    date.getDate()
  );
  selectedDate.value = localDate;
  emit('update:modelValue', localDate);
}

function previousMonth() {
  if (isPreviousMonthDisabled.value) return;
  
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