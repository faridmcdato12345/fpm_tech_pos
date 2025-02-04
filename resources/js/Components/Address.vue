<template>
    <div class="text-black grid grid-cols-2 gap-2">
        <div class="flex flex-col space-y-2">
            <!-- Region Selection -->
            <label for="region" class="text-white text-left">Select Region:</label>
            <select id="region" v-model="selectedRegion" @change="updateProvinces">
                <option value="" disabled>Select a region</option>
                <option v-for="(region, key) in addressLists" :key="key" :value="key">
                    {{ region.region_name }}
                </option>
            </select>
        </div>
        <div class="flex flex-col  space-y-2">
            <!-- Province Selection -->
            <label for="province" class="text-white text-left">Select Province:</label>
            <select id="province" v-model="selectedProvince" @change="updateMunicipality" :disabled="!selectedRegion">
                <option value="" disabled>Select a province</option>
                <option v-for="(province, name) in provinces" :key="name" :value="name">
                    {{ name }}
                </option>
            </select>
        </div>
        <div class="flex flex-col  space-y-2">
            <!-- Municipality Selection -->
            <label for="municipality" class="text-white text-left">Select Municipality:</label>
            <select id="municipality" v-model="selectedMunicipality" @change="updateBarangays"
                :disabled="!selectedProvince">
                <option value="" disabled>Select a municipality</option>
                <option v-for="(municipality, name) in municipalities" :key="name" :value="name">
                    {{ name }}
                </option>
            </select>
        </div>
        <div class="flex flex-col  space-y-2">
            <!-- Barangay Selection -->
            <label for="barangay" class="text-white text-left">Select Barangay:</label>
            <select id="barangay" v-model="selectedBarangay" @change="emitSelections" :disabled="!selectedMunicipality">
                <option value="" disabled>Select a barangay</option>
                <option v-for="barangay in barangays" :key="barangay" :value="barangay">
                    {{ barangay }}
                </option>
            </select>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    addressLists: {
        type: Array,
        default: () => []
    },
    resetKey: {
        type: Number,
        default: 0,
    },
})
const emit = defineEmits(['updateSelections']);
console.log(props.addressLists)
// Reactive references for selected values
const selectedRegion = ref('');
const selectedProvince = ref('');
const selectedMunicipality = ref('');
const selectedBarangay = ref('');

const provinces = ref({});
const municipalities = ref({});
const barangays = ref([]);

// Function to update provinces based on selected region
function updateProvinces() {
    selectedProvince.value = '';
    selectedMunicipality.value = '';
    selectedBarangay.value = '';
    provinces.value = selectedRegion.value
        ? props.addressLists[selectedRegion.value].province_list
        : {};
    emitSelections();
}
// Function to update municipalities based on selected province
function updateMunicipality() {
    selectedMunicipality.value = '';
    selectedBarangay.value = '';
    municipalities.value = selectedProvince.value
        ? provinces.value[selectedProvince.value].municipality_list
        : {};
    emitSelections();
}
// Function to update barangays based on selected municipality
function updateBarangays() {
    selectedBarangay.value = '';
    barangays.value = selectedMunicipality.value
        ? municipalities.value[selectedMunicipality.value].barangay_list
        : [];
    emitSelections();
}

// Emit the selected region, province, and barangay to the parent component
function emitSelections() {
    emit('updateSelections', {
        region: selectedRegion.value,
        province: selectedProvince.value,
        barangay: selectedBarangay.value,
        municipality: selectedMunicipality.value
    });
}
const resetState = () => {
    // Reset the internal state of the Address component
    console.log('Address component reset triggered.');
    selectedRegion.value = ''
    selectedProvince.value = ''
    selectedBarangay.value = ''
    selectedMunicipality.value = ''
};

watch(
    () => props.resetKey,
    () => {
        resetState();
    }
);
// Watchers to ensure changes in selections are emitted immediately
watch([selectedRegion, selectedProvince, selectedBarangay, selectedMunicipality], emitSelections);
</script>
