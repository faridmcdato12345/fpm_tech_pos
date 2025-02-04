<template>
    <div class="outer-container">
        <div class="inner-container">
            <div class="analog-clock">
                <div class="analog-clock-face">
                    <div class="dots"></div>
                    <div class="dots"></div>
                    <div class="dots"></div>
                    <div class="dots"></div>
                    <div class="dots"></div>
                    <div class="dots"></div>
                    <div v-bind:style="{ transform: `rotate(${hourDeg}deg)` }" class="hand hour-hand bg-slate-500">
                    </div>
                    <div v-bind:style="{ transform: `rotate(${minDeg}deg)` }" class="hand min-hand bg-slate-600">
                    </div>
                    <div v-bind:style="{ transform: `rotate(${secDeg}deg)` }" class="hand sec-hand"></div>
                </div>
            </div>

            <div class="digital-clock">
                <div class="digit digit-hour bg-teal-400">{{ hour }}</div>
                <div class="digit digit-min bg-teal-400">{{ min }}</div>
                <div class="digit digit-sec bg-teal-400">{{ sec }}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const hour = ref('');
const min = ref('');
const sec = ref('');
const secDeg = ref(0);
const minDeg = ref(0);
const hourDeg = ref(0);

function zeroPadded(num) {
    return num < 10 ? `0${num}` : num.toString();
}

const clock = () => {
    let now = new Date();
    hour.value = zeroPadded(now.getHours());
    min.value = zeroPadded(now.getMinutes());
    sec.value = zeroPadded(now.getSeconds());
    secDeg.value = ((sec.value / 60) * 360) + 90;
    minDeg.value = ((min.value / 60) * 360) + ((sec.value / 60) * 6) + 90;
    hourDeg.value = ((hour.value / 12) * 360) + ((min.value / 60) * 30) + 90;
};

const updateClock = () => {
    setInterval(clock, 1000);
};

onMounted(() => {
    updateClock();
});
</script>

<style scoped>
/* ==================
 *  BASE
 * ================== */
body {
    font-family: 'Open Sans Condensed', sans-serif;
    color: black;
}

.outer-container {
    display: flex;
    align-items: center;
    flex-direction: column;
    padding-top: 5px;
}

.inner-container {
    padding: 30px;
    border-radius: 10px;
}

h1 {
    font-family: 'Pacifico', cursive;
    font-size: 40px;
    color: #ffffff;
    text-shadow: 2px 3px 3px gray;
}

/* ==================
 *  Analog Clock
 * ================== */

/* *** CLOCK *** */

.analog-clock {
    border: 10px solid red;
    margin: 0 auto;
    width: 180px;
    height: 180px;
    border-radius: 50%;
}

.analog-clock-face {
    height: 100%;
    transform: translateY(50%);
}

.analog-clock-face::before {
    content: '';
    width: 8px;
    height: 8px;
    position: absolute;
    background: white;
    left: 50%;
    z-index: 10;
    transform: translate(-50%, -50%);
    border: 2px solid red;
    border-radius: 50%;
}

/* *** DOTS *** */

.dots:nth-of-type(1) {
    transform: rotate(0deg);
}

.dots:nth-of-type(2) {
    transform: rotate(90deg);
}

.dots:nth-of-type(3) {
    transform: rotate(30deg);
}

.dots:nth-of-type(4) {
    transform: rotate(60deg);
}

.dots:nth-of-type(5) {
    transform: rotate(120deg);
}

.dots:nth-of-type(6) {
    transform: rotate(150deg);
}

.dots::before,
.dots::after {
    content: '';
    position: absolute;
    background: orange;
    width: 7px;
    height: 2px;
    border-radius: 30px;
    top: -3px;
}

.dots::after {
    left: 7px;
}

.dots::before {
    right: 7px;
}

.dots:nth-of-type(1)::before,
.dots:nth-of-type(1)::after,
.dots:nth-of-type(2)::before,
.dots:nth-of-type(2)::after {
    background: orange;
    width: 10px;
    height: 4px;
}

/* *** HAND *** */

.hand {
    transform-origin: 100%;
    border-radius: 10px;
    position: absolute;
    right: 50%;
}

.hand.hour-hand {
    width: 25%;
    height: 5px;
    z-index: 1;
}

.hand.min-hand {
    width: 37%;
    height: 4px;
}

.hand.sec-hand {
    background: red;
    width: 39%;
    height: 2px;
}

/* ==================
 *  Digital Clock
 * ================== */

.digital-clock {
    margin-top: 30px;
    display: flex;
    justify-content: center;
    font-size: 50px;
}

.digit {
    margin: 0 5px;
    height: 80px;
    width: 75px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 1px 3px 5px gray;
    border-radius: 5px;
    color: var(--color-digit);
}
</style>
