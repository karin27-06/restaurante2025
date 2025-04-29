import { Pagination } from '@/interface/paginacion';
import { AlmacenRequest, AlmacenResource, AlmacenUpdateRequest } from '@/pages/panel/almacen/interface/Almacen';
import { AlmacenServices } from '@/services/almacenServices';
import { showSuccessMessage } from '@/utils/message';
import { reactive } from 'vue';

export const useAlmacen = () => {
    const principal = reactive<{
        almacenList: AlmacenResource[];
        paginacion: Pagination;
        loading: boolean;
        filter: string;
        idAlmacen: number;
        stateModal: {
            update: boolean;
            delete: boolean;
        };
        almacenData: AlmacenResource;
    }>({
        almacenList: [],
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
        idAlmacen: 0,
        stateModal: {
            update: false,
            delete: false,
        },
        almacenData: {
            id: 0,
            name: '',
            created_at: '',
            state: true,
        },
    });

    // reset almacen data
    const resetAlmacenData = () => {
        principal.almacenData = {
            id: 0,
            name: '',
            created_at: '',
            state: true,
        };
    };
    // loading almacens
    const loadingAlmacens = async (page: number = 1, name: string = '', status: boolean = true) => {
        if (status) {
            principal.loading = true;
            try {
                const response = await AlmacenServices.index(page, name);
                principal.almacenList = response.almacens;
                principal.paginacion = response.pagination;
                console.log(response);
            } catch (error) {
                console.error(error);
            } finally {
                principal.loading = false;
            }
        }
    };

    // creating almacen
    const createAlmacen = async (data: AlmacenRequest) => {
        try {
            await AlmacenServices.store(data);
        } catch (error) {
            console.error(error);
        }
    };

    // get Almacen by id
    const getAlmacenById = async (id: number) => {
        try {
            if (id === 0) {
                principal.almacenData = {
                    id: 0,
                    name: '',
                    created_at: '',
                    state: true,
                };
                return;
            }
            const response = await AlmacenServices.show(id);
            if (response.state) {
                principal.almacenData = response.almacen;
                console.log(principal.almacenData.name);
                principal.idAlmacen = response.almacen.id;
                principal.stateModal.update = true;
            }
        } catch (error) {
            console.error(error);
        }
    };

    //update almacen
    const updateAlmacen = async (id: number, data: AlmacenUpdateRequest) => {
        try {
            const response = await AlmacenServices.update(id, data);
            if (response.state) {
                showSuccessMessage('Almacen actualizado', 'El almacen se actualizo correctamente');
                principal.stateModal.update = false;
                loadingAlmacens(principal.paginacion.current_page, principal.filter);
            }
        } catch (error) {
            console.error(error);
        }
    };

    //delete almacen
    const deleteAlmacen = async (id: number) => {
        try {
            const response = await AlmacenServices.destroy(id);
            console.log(response.state);
            if (response.state) {
                showSuccessMessage('Almacen eliminado', 'El almacen se elimino correctamente');
                principal.stateModal.delete = false;
                loadingAlmacens(principal.paginacion.current_page, principal.filter);
            }
        } catch (error) {
            console.error(error);
        } finally {
            principal.stateModal.delete = false;
        }
    };

    return {
        principal,
        loadingAlmacens,
        createAlmacen,
        getAlmacenById,
        resetAlmacenData,
        updateAlmacen,
        deleteAlmacen,
    };
};
