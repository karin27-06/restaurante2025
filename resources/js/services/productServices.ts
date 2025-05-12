import {
    ProductDeleteResponse,
    ProductRequest,
    ProductResponse,
    ProductUpdateRequest,
    showProductResponse,
} from '@/pages/panel/product/interface/Product';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

export const ProductServices = {
    // List products
    async index(page: number, name: string): Promise<ProductResponse> {
        const response = await axios.get(`/panel/listar-products?page=${page}&name=${encodeURIComponent(name)}`);
        return response.data;
    },

    // Inertia
    async store(data: ProductRequest) {
        router.post(route('panel.products.store'), data);
    },

    // Show product
    async show(id: number): Promise<showProductResponse> {
        const response = await axios.get(`/panel/products/${id}`);
        return response.data;
    },

    // Update product
    async update(id: number, data: ProductUpdateRequest): Promise<showProductResponse> {
        const response = await axios.put(`/panel/products/${id}`, data);
        return response.data;
    },

    // Delete product
    async destroy(id: number): Promise<ProductDeleteResponse> {
        const response = await axios.delete(`/panel/products/${id}`);
        return response.data;
    },


     getAlmacens() {
        return axios.get('/panel/almacens-option');
    },

    getCategories() {
        return axios.get('/panel/categories-option');
    },
};
