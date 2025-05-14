import { Pagination } from '@/interface/paginacion';
import { FloorRequest, FloorResource, FloorUpdateRequest } from '@/pages/panel/floor/interface/Floor';
import { FloorServices } from '@/services/floorServices';
import { showSuccessMessage } from '@/utils/message';
import { reactive } from 'vue';

export const useFloor = () => {
    const principal = reactive<{
        floorList: FloorResource[];
        paginacion: Pagination;
        loading: boolean;
        filter: string;
        idFloor: number;
        stateModal: {
            update: boolean;
            delete: boolean;
        };
        floorData: FloorResource;
    }>({
        floorList: [],
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
        idFloor: 0,
        stateModal: {
            update: false,
            delete: false,
        },
        floorData: {
            id: 0,
            name: '',
            description: '',
            state: true,
            created_at: '',
            updated_at: '',
        },
    });

    // Reset floor data
    const resetFloorData = () => {
        principal.floorData = {
            id: 0,
            name: '',
            description: '',
            state: true,
            created_at: '',
            updated_at: '',
        };
    };

    // Load floors
    const loadingFloors = async (page: number = 1, name: string = '', state: boolean = true) => {
        if (state) {
            principal.loading = true;
            try {
                const response = await FloorServices.index(page, name);
                principal.floorList = response.floors;
                principal.paginacion = response.pagination;
            } catch (error) {
                console.error(error);
            } finally {
                principal.loading = false;
            }
        }
    };

    // Create floor
    const createFloor = async (data: FloorRequest) => {
        try {
            const response = await FloorServices.store(data);
            if (response.state) {
                showSuccessMessage('Piso creado', 'El piso se creó correctamente');
                loadingFloors(principal.paginacion.current_page, principal.filter);
            }
        } catch (error) {
            console.error(error);
        }
    };

    // Get floor by ID
    const getFloorById = async (id: number) => {
        try {
            if (id === 0) {
                resetFloorData();
                return;
            }
            const response = await FloorServices.show(id);
            if (response.state) {
                principal.floorData = response.floor;
                principal.idFloor = response.floor.id;
                principal.stateModal.update = true;
            }
        } catch (error) {
            console.error(error);
        }
    };

    // Update floor
    const updateFloor = async (id: number, data: FloorUpdateRequest) => {
        try {
            const response = await FloorServices.update(id, data);
            if (response.state) {
                showSuccessMessage('Piso actualizado', 'El piso se actualizó correctamente');
                principal.stateModal.update = false;
                loadingFloors(principal.paginacion.current_page, principal.filter);
            }
        } catch (error) {
            console.error(error);
        }
    };

    // Delete floor
    const deleteFloor = async (id: number) => {
        try {
            const response = await FloorServices.destroy(id);
            if (response.state) {
                showSuccessMessage('Piso eliminado', 'El piso se eliminó correctamente');
                principal.stateModal.delete = false;
                loadingFloors(principal.paginacion.current_page, principal.filter);
            }
        } catch (error) {
            console.error(error);
        } finally {
            principal.stateModal.delete = false;
        }
    };

    return {
        principal,
        loadingFloors,
        createFloor,
        getFloorById,
        resetFloorData,
        updateFloor,
        deleteFloor,
    };
};
