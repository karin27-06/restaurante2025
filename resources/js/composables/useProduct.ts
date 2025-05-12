import { Pagination } from '@/interface/paginacion';
import { ProductRequest, ProductResource, ProductUpdateRequest } from '@/pages/panel/product/interface/Product';
import { ProductServices } from '@/services/productServices';
import { showSuccessMessage } from '@/utils/message';
import { reactive } from 'vue';

export const useProduct = () => {
    const principal = reactive<{
        productList: ProductResource[];
        paginacion: Pagination;
        loading: boolean;
        filter: string;
        idProduct: number;
        stateModal: {
            update: boolean;
            delete: boolean;
        };
        productData: ProductResource;
    }>({
        productList: [],
        paginacion: {
            total: 0,
            current_page: 0,
            per_page: 0,
            last_page: 0,
            from: 0,
            to: 0,
        },
        loading: false,
        filter: '',
        idProduct: 0,
        stateModal: {
            update: false,
            delete: false,
        },
        productData: {
            id: 0,
            name: '',
            idCategory: 0,
            details: '',
            idAlmacen: 0,
            state: true,
            created_at: '',
            updated_at: '',
        },
    });

    // Reset product data
    const resetProductData = () => {
        principal.productData = {
            id: 0,
            name: '',
            idCategory: 0,
            details: '',
            idAlmacen: 0,
            state: true,
            created_at: '',
            updated_at: '',
        };
    };

    // Loading products
    const loadingProducts = async (page: number = 1, name: string = '', state: boolean = true) => {
        if (state) {
            principal.loading = true;
            try {
                const response = await ProductServices.index(page, name);
                principal.productList = response.products;
                principal.paginacion = response.pagination;
                console.log(response);
            } catch (error) {
                console.error(error);
            } finally {
                principal.loading = false;
            }
        }
    };

    // Creating products
    const createProduct = async (data: ProductRequest) => {
        try {
            await ProductServices.store(data);
        } catch (error) {
            console.error(error);
        }
    };

    // Get product by ID
    const getProductById = async (id: number) => {
        try {
            if (id === 0) {
                principal.productData = {
                    id: 0,
                    name: '',
                    idCategory: 0,
                    details: '',
                    idAlmacen: 0,
                    state: true,
                    created_at: '',
                    updated_at: '',
                };
                return;
            }
            const response = await ProductServices.show(id);
            if (response.state) {
                principal.productData = response.product;
         
                principal.idProduct = response.product.id;
                
                principal.stateModal.update = true;
            }
        } catch (error) {
            console.error(error);
        }
    };

    // Update product
    const updateProduct = async (id: number, data: ProductUpdateRequest) => {
        try {
            const response = await ProductServices.update(id, data);
            if (response.state) {
                showSuccessMessage('Producto actualizado', 'El producto se actualizó correctamente');
                principal.stateModal.update = false;
                loadingProducts(principal.paginacion.current_page, principal.filter);
            }
        } catch (error) {
            console.error(error);
        }
    };

    // Delete product
    const deleteProduct = async (id: number) => {
        try {
            const response = await ProductServices.destroy(id);
            console.log(response.state);
            if (response.state) {
                showSuccessMessage('Producto eliminado', 'El producto se eliminó correctamente');
                principal.stateModal.delete = false;
                loadingProducts(principal.paginacion.current_page, principal.filter);
            }
        } catch (error) {
            console.error(error);
        } finally {
            principal.stateModal.delete = false;
        }
    };

    return {
        principal,
        loadingProducts,
        createProduct,
        getProductById,
        resetProductData,
        updateProduct,
        deleteProduct,
    };
};
