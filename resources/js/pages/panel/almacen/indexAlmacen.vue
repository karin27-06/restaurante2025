<template>
    <Head title="Almacenes"></Head>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <div class="mt-4 mb-4 flex items-center justify-between px-6">
                    <FilterAlmacen @search="searchAlmacen" />
                </div>
                <TableAlmacen
                    :almacen-list="principal.almacenList"
                    :almacen-paginate="principal.paginacion"
                    @page-change="handlePageChange"
                    @open-modal="getIdAlmacen"
                    @open-modal-delete="openDeleteModal"
                    :loading="principal.loading"
                />
                <EditAlmacen
                    :almacen-data="principal.almacenData"
                    :modal="principal.stateModal.update"
                    @emit-close="closeModal"
                    @update-almacen="emitUpdateAlmacen"
                />
                <DeleteAlmacen
                    :modal="principal.stateModal.delete"
                    :itemId="principal.idAlmacen"
                    title="Eliminar Almacen"
                    description="¿Está seguro de que desea eliminar este almacen?"
                    @close-modal="closeModalDelete"
                    @delete-item="emitDeleteAlmacen"
                />
            </div>
        </div>
    </AppLayout>
</template>
<script setup lang="ts">
import { useAlmacen } from '@/composables/useAlmacen';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import DeleteAlmacen from '../../../components/delete.vue';
import FilterAlmacen from '../../../components/filter.vue';
import EditAlmacen from './components/editAlmacen.vue';
import TableAlmacen from './components/tableAlmacen.vue';
import { AlmacenUpdateRequest } from './interface/Almacen';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Crear almacen',
        href: '/panel/almacens/create',
    },
    {
        title: 'Almacenes',
        href: '/panel/almacens',
    },
];

onMounted(() => {
    loadingAlmacens();
});

const { principal, loadingAlmacens, getAlmacenById, updateAlmacen, deleteAlmacen } = useAlmacen();

// get pagination
const handlePageChange = (page: number) => {
    console.log(page);
    loadingAlmacens(page);
};

// get Almacene by id for edit
const getIdAlmacen = (id: number) => {
    getAlmacenById(id);
};

// close modal
const closeModal = (open: boolean) => {
    principal.stateModal.update = open;
};

// close modal delete
const closeModalDelete = (open: boolean) => {
    principal.stateModal.delete = open;
};

//update almacen
const emitUpdateAlmacen = (almacen: AlmacenUpdateRequest, id_almacen: number) => {
    updateAlmacen(id_almacen, almacen);
};

// delete almacen
const openDeleteModal = (almacenid: number) => {
    principal.stateModal.delete = true;
    principal.idAlmacen = almacenid;
    console.log('Eliminar almacen con ID:', almacenid);
};

// delete almacen
const emitDeleteAlmacen = (almacenId: number | string) => {
    deleteAlmacen(Number(almacenId));
};

// search Service
const searchAlmacen = (text: string) => {
    loadingAlmacens(1, text);
};
</script>
<style lang="css" scoped></style>
