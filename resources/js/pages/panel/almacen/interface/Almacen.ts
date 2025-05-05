import { Pagination } from '@/interface/paginacion';

export type AlmacenResource = {
    id: number;
    name: string;
    state: boolean;
    created_at: string;
};

export type AlmacenRequest = {
    name: string;
    state: 'activo' | 'inactivo';
};

export type showAlmacenResponse = {
    state: boolean;
    message: string;
    almacen: AlmacenResource;
};

export type AlmacenDeleteResponse = {
    state: boolean;
    message: string;
};

export type AlmacenUpdateRequest = {
    name: string;
    state: 'activo' | 'inactivo';
};

export type AlmacenResponse = {
    almacens: AlmacenResource[];
    pagination: Pagination;
};
