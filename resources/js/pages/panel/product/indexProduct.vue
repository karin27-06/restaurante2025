<template>
    <Head title="product"></Head>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-2">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <Filterproduct @search="searchproduct" />
                <Tableproduct
                    :product-list="principal.productList"
                    :product-paginate="principal.paginacion"
                    @page-change="handlePageChange"
                    @open-modal="getIdproduct"
                    @open-modal-delete="openDeleteModal"
                    :loading="principal.loading"
                />
                <Editproduct
                    :product-data="principal.productData"
                    :modal="principal.stateModal.update"
                    @emit-close="closeModal"
                    @update-product="emitUpdateproduct"
                />
                <Deleteproduct
                    :modal="principal.stateModal.delete"
                    :itemId="principal.idproduct"
                    title="Eliminar Producto"
                    description="¿Está seguro de que desea eliminar este Producto?"
                    @close-modal="closeModalDelete"
                    @delete-item="emitDeleteproduct"
                />
            </div>
        </div>
    </AppLayout>
</template>
<script setup lang="ts">
import Deleteproduct from '@/components/delete.vue';
import Filterproduct from '@/components/filter.vue';
import { useProduct } from '@/composables/useProduct';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { ProductServices } from '@/services/productServices'; // Asegúrate de que la ruta sea correcta

import Editproduct from './components/editproduct.vue';
import Tableproduct from './components/tableproduct.vue';
import { productUpdateRequest } from './interface/product';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Crear Producto',
        href: '/panel/products/create',
    },
    /*{
        title: 'Exportar a Excel',
        href: '/panel/reports/export-excel-products',
        download: true,
    },
    {
        title: 'Exportar a PDF',
        href: '/panel/reports/export-pdf-products',
        download: true,
    },*/
    {
        title: 'Productos',
        href: '/panel/products',
    },
];




const { principal, loadingProducts, getProductById, updateProduct, deleteProduct } = useProduct();
onMounted(() => {
    loadingProducts();
});
// get pagination
const handlePageChange = (page: number) => {
    console.log(page);
    loadingProducts(page);
};
// get product by id for edit
const getIdproduct = (id: number) => {
    getProductById(id);
};
// close modal
const closeModal = (open: boolean) => {
    principal.stateModal.update = open;
};
// close modal delete
const closeModalDelete = (open: boolean) => {
    principal.stateModal.delete = open;
};

// update product
const emitUpdateproduct = (product: productUpdateRequest, id_product: number) => {
    updateProduct(id_product, product);
};

// delete product
const openDeleteModal = (productId: number) => {
    principal.stateModal.delete = true;
    principal.idproduct = productId;
    console.log('Eliminar Producto con ID:', productId);
};
// delete product
const emitDeleteproduct = (productId: number | string) => {
    deleteProduct(Number(productId));
};
// search product
const searchproduct = (text: string) => {
    loadingProducts(1, text);
};


</script>
<style lang="css" scoped>
.relative.min-h-\[100vh\].flex-1.rounded-xl.border.border-sidebar-border\/70.dark\:border-sidebar-border.md\:min-h-min {
    padding: 20px;
}
</style>
