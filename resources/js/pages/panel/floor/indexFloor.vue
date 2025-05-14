<template>
    <Head title="floor"></Head>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-2">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <FilterFloor @search="searchFloor" />
                <TableFloor
                    :floor-list="principal.floorList"
                    :floor-paginate="principal.paginacion"
                    @page-change="handlePageChange"
                    @open-modal="getIdFloor"
                    @open-modal-delete="openDeleteModal"
                    :loading="principal.loading"
                />
                <EditFloor
                    :floor-data="principal.floorData"
                    :modal="principal.stateModal.update"
                    @emit-close="closeModal"
                    @update-floor="emitUpdateFloor"
                />
                <DeleteFloor
                    :modal="principal.stateModal.delete"
                    :itemId="principal.idFloor"
                    title="Eliminar Piso"
                    description="¿Está seguro de que desea eliminar este Piso?"
                    @close-modal="closeModalDelete"
                    @delete-item="emitDeleteFloor"
                />
            </div>
        </div>
    </AppLayout>
</template>
<script setup lang="ts">
import DeleteFloor from '@/components/delete.vue';
import FilterFloor from '@/components/filter.vue';
import { useFloor } from '@/composables/useFloor';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import EditFloor from './components/editFloor.vue';
import TableFloor from './components/tableFloor.vue';
import { FloorUpdateRequest } from './interface/Floor';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Crear Piso',
        href: '/panel/floors/create',
    },
    /*{
        title: 'Exportar a Excel',
        href: '/panel/reports/export-excel-floors',
        download: true,
    },
    {
        title: 'Exportar a PDF',
        href: '/panel/reports/export-pdf-floors',
        download: true,
    },*/
    {
        title: 'Pisos',
        href: '/panel/floors',
    },
];

onMounted(() => {
    loadingFloors();
});

const { principal, loadingFloors, getFloorById, updateFloor, deleteFloor } = useFloor();

// get pagination
const handlePageChange = (page: number) => {
    console.log(page);
    loadingFloors(page);
};
// get floor by id for edit
const getIdFloor = (id: number) => {
    getFloorById(id);
};
// close modal
const closeModal = (open: boolean) => {
    principal.stateModal.update = open;
};
// close modal delete
const closeModalDelete = (open: boolean) => {
    principal.stateModal.delete = open;
};

// update floor
const emitUpdateFloor = (floor: FloorUpdateRequest, id_floor: number) => {
    updateFloor(id_floor, floor);
};

// delete floor
const openDeleteModal = (floorId: number) => {
    principal.stateModal.delete = true;
    principal.idFloor = floorId;
    console.log('Eliminar categoría con ID:', floorId);
};
// delete floor
const emitDeleteFloor = (floorId: number | string) => {
    deleteFloor(Number(floorId));
};
// search floor
const searchFloor = (text: string) => {
    loadingFloors(1, text);
};
</script>
<style lang="css" scoped>
.relative.min-h-\[100vh\].flex-1.rounded-xl.border.border-sidebar-border\/70.dark\:border-sidebar-border.md\:min-h-min {
    padding: 20px;
}
</style>
