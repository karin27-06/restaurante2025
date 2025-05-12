<template>
    <div class="container mx-auto px-4 py-2">
        <LoadingTable v-if="loading" :headers="5" :row-count="10" />
        <div v-else class="space-y-4">
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm dark:border-gray-700 dark:shadow-none">
                <Table class="w-full">
                    <TableHeader class="bg-gray-50 dark:bg-gray-800/50">
                        <TableRow class="hover:bg-transparent">
                            <TableHead class="px-4 py-3 font-medium text-gray-700 dark:text-gray-300">ID</TableHead>
                            <TableHead class="px-4 py-3 font-medium text-gray-700 dark:text-gray-300">NOMBRE</TableHead>
                            <TableHead class="px-4 py-3 font-medium text-gray-700 dark:text-gray-300">CATEGORÍA</TableHead>
                            <TableHead class="px-4 py-3 font-medium text-gray-700 dark:text-gray-300">ALMACEN</TableHead>
                            <TableHead class="px-4 py-3 font-medium text-gray-700 dark:text-gray-300">ESTADO</TableHead>
                            <TableHead class="px-4 py-3 font-medium text-gray-700 dark:text-gray-300">FECHA</TableHead>
                            <TableHead class="px-4 py-3 font-medium text-gray-700 dark:text-gray-300">ACCIONES</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <TableRow
                            v-for="product in productList"
                            :key="product.id"
                            class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/30"
                        >
                            <TableCell class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ product.id }}</TableCell>
                            <TableCell class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ product.name }}</TableCell>
                            <TableCell class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ product.category.name }}</TableCell>
                            <TableCell class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ product.almacen.name }}</TableCell>
                              <TableCell class="px-4 py-3">
                                <span
                                    v-if="product.state === true"
                                    class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-medium text-green-800 dark:bg-green-900/30 dark:text-green-200"
                                >
                                    <span class="mr-1 h-2 w-2 rounded-full bg-green-500 dark:bg-green-400"></span>Activo
                                </span>
                                <span
                                    v-else
                                    class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-medium text-red-800 dark:bg-red-900/30 dark:text-red-200"
                                >
                                    <span class="mr-1 h-2 w-2 rounded-full bg-red-500 dark:bg-red-400"></span>
                                    Inactivo
                                </span>
                            </TableCell>
                            <TableCell class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ product.created_at }}</TableCell>
                            <TableCell class="flex justify-start space-x-2 px-4 py-3">
                               <Button
                                            variant="ghost"
                                            size="sm"
                                            class="action-button"
                                            @click="openModal(product.id)"
                                            title="Editar Producto"
                                        >
                                            <UserPen class="action-icon" />
                                            <span class="sr-only">Editar Producto</span>
                                        </Button>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            class="action-button"
                                            @click="openModalDelete(product.id)"
                                            title="Eliminar Producto"
                                        >
                                            <Trash class="action-icon" />
                                            <span class="sr-only">Eliminar Producto</span>
                                        </Button>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <PaginationCategory :meta="productPaginate" @page-change="$emit('page-change', $event)" class="mt-6" />
        </div>
    </div>
</template>

<script setup lang="ts">
import LoadingTable from '@/components/loadingTable.vue';
import PaginationCategory from '@/components/pagination.vue';
import Button from '@/components/ui/button/Button.vue';
import { Table, TableBody, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import TableCell from '@/components/ui/table/TableCell.vue';

import { useToast } from '@/components/ui/toast';
import { Pagination } from '@/interface/paginacion';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { Trash, UserPen } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { ProductResource } from '../interface/Product';

const { toast } = useToast();

const emit = defineEmits<{
    (e: 'page-change', page: number): void;
    (e: 'open-modal', id_product: number): void;
    (e: 'open-modal-delete', id_product: number): void;
}>();

const page = usePage<SharedData>();

const message = ref(page.props.flash?.message || '');

onMounted(() => {
    if (message.value) {
        toast({
            title: 'Notificación',
            description: message.value,
        });
    }
});

const { productList, productPaginate } = defineProps<{
    productList: ProductResource[];
    productPaginate: Pagination;
    loading: boolean;
}>();

const openModal = (id: number) => {
    emit('open-modal', id);
};

const openModalDelete = (id: number) => {
    emit('open-modal-delete', id);
};
</script>

<style scoped lang="css">
.pagination-summary {
    text-align: center;
}
</style>
