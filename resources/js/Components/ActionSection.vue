<template>
    <div>
        <ActionButton @button-clicked="$emit('button-clicked', $event)" :lists="actionButtonList" />
        <div class="mb-4">
            <SearchField :searchQuery="modelValue" @update:searchQuery="updateSearchQuery" />
        </div>
    </div>
</template>

<script setup>
import ActionButton from "@/Components/ActionButton.vue";
import SearchField from "@/Components/SearchField.vue";
defineProps(["actionButtonList", "searchQuery"]);
</script>
