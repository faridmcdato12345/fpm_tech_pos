<template>
    <Sidebar>
        <SidebarHeader />
        <SidebarContent>
            <SidebarGroup />
            <SidebarGroup />
        </SidebarContent>
        <SidebarFooter />
    </Sidebar>
</template>

<script setup>
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarHeader,
} from "@/Shadcn/ui/sidebar";
</script>
