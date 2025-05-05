import { Pagination } from "@/interface/paginacion";

export type CategoryResource = {
  id: number;
  name: string;
  state: boolean;
  created_at: string;
  updated_at: string;
};

export type CategoryRequest = {
    name: string;
    state: 'activo' | 'inactivo';
};

export type showCategoryResponse = {
    state: boolean;
    message: string;
    category: CategoryResource;
};

export type CategoryDeleteResponse = {
    state: boolean;
    message: string;
};

export type CategoryUpdateRequest = {
    name: string;
    state: 'activo' | 'inactivo';
};
export type CategoryResponse = {
  categories: CategoryResource[];
  pagination: Pagination;
};