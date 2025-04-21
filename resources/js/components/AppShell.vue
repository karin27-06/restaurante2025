<script setup lang="ts">
import { SidebarProvider } from '@/components/ui/sidebar';
import { onMounted, ref } from 'vue';

interface Props {
    variant?: 'header' | 'sidebar';
}

defineProps<Props>();

const isOpen = ref(true);

onMounted(() => {
    isOpen.value = localStorage.getItem('sidebar') !== 'false';
});

const handleSidebarChange = (open: boolean) => {
    isOpen.value = open;
    localStorage.setItem('sidebar', String(open));
};
</script>

<template>
    <!-- Aseguramos que el contenido ocupe toda la altura de la pantalla -->
    <div v-if="variant === 'header'" class="flex h-screen w-full flex-col">
        <slot />
    </div>

    <!-- Aseguramos que el sidebar y el contenido estén organizados correctamente -->
    <SidebarProvider v-else :default-open="isOpen" :open="isOpen" @update:open="handleSidebarChange">
        <div class="flex h-screen w-full">
            <!-- Por ahora TRABAJAR CON ESTO, falta arreglar el shell de visualizacion, ya qu
             queda un espacio -->
            <div :class="['flex-1 overflow-auto transition-all duration-300', isOpen ? 'ml-64' : 'ml-16']">
                <slot />
            </div>
        </div>
    </SidebarProvider>
</template>
