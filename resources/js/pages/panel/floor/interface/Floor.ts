import { Pagination } from "@/interface/paginacion";

export type FloorResource = {
  id: number;
  name: string;
  description: string;
  state: boolean;
  created_at: string;
  updated_at: string;
};

export type FloorRequest = {
  name: string;
  description: string;
  state: 'activo' | 'inactivo';
};

export type ShowFloorResponse = {
  state: boolean;
  message: string;
  floor: FloorResource;
};

export type FloorDeleteResponse = {
  state: boolean;
  message: string;
};

export type FloorUpdateRequest = {
  name: string;
  description: string;
  state: 'activo' | 'inactivo';
};

export type FloorResponse = {
  floors: FloorResource[];
  pagination: Pagination;
};
