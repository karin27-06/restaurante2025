import { Pagination } from "@/interface/paginacion";

export type ProductResource = {
  id: number;
  name: string;
  idCategory: number;
  details: string;
  idAlmacen: number;
  state: boolean;
  created_at: string;
  updated_at: string;
};

export type ProductRequest = {
  name: string;
  idCategory: number;
  details: string;
  idAlmacen: number;
  state: 'activo' | 'inactivo';
};

export type showProductResponse = {
  state: boolean;
  message: string;
  product: ProductResource;
};

export type ProductDeleteResponse = {
  state: boolean;
  message: string;
};

export type ProductUpdateRequest = {
  name: string;
  idCategory: number;
  details: string;
  idAlmacen: number;
  state: 'activo' | 'inactivo';
};

export type ProductResponse = {
  products: ProductResource[];
  pagination: Pagination;
};
