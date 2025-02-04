<template>
    <div>
        <Modal v-if="modals.modalPrint" @close="$emit('close-modal', 'modalPrint')" title="Print">
            <!-- Print content -->
        </Modal>
        <Modal v-if="modals.modalChangeQuantity" @close="$emit('close-modal', 'modalChangeQuantity')"
            title="Change Quantity">
            <!-- Change quantity content -->
        </Modal>
        <!-- Add other modals here -->
    </div>
</template>

<script setup>
import Modal from "@/Components/Modal.vue";
defineProps(["modals", "data"]);
</script>
