import api from "@/lib/axiosConfig";

export interface ProductVariant {
  id: number;
  product_id: number;
  name: string;
  price: string;
  stock_quantity: number;
}

// --- هنا قمنا بالتعديل ---
export interface Product {
  id: number;
  name: string;
  description: string;
  price: string;
  variants_count: number; // <-- تمت الإضافة هنا
  variants?: ProductVariant[];
}

export const getProducts = async (): Promise<Product[]> => {
  const response = await api.get('/products');
  return response.data;
};

export const getProductById = async (id: string | number): Promise<Product> => {
  const response = await api.get(`/products/${id}`);
  return response.data;
};