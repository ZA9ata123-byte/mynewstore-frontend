import api from '@/lib/axiosConfig';

// --- Interfaces ---

export interface OptionValue { id?: number; value: string; }
export interface Option { id?: number; name: string; values: OptionValue[]; }
export interface Variant {
  id: number;
  price: number;
  sku: string | null;
  stock: number;
  optionValues: { id: number; value: string; option: { id: number; name: string; }; }[];
}
export interface Product {
  id: number;
  name: string;
  description: string;
  short_description?: string;
  price: number;
  price_range: string;
  category_id: number;
  product_type: 'simple' | 'variable';
  stock?: number;
  total_stock: number;
  options?: Option[];
  variants?: Variant[];
}
export interface Paginator<T> {
    current_page: number;
    data: T[];
    total: number;
    // ... other pagination properties
}

// --- API Functions ---

export const getAdminProducts = async (): Promise<Paginator<Product>> => {
    try {
        const response = await api.get<Paginator<Product>>('/admin/products');
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const getAdminProductById = async (productId: string): Promise<Product> => {
    try {
        const response = await api.get<Product>(`/admin/products/${productId}`);
        return response.data;
    } catch (error) {
        throw error;
    }
};

// --- هنا تم إصلاح الخطأ ---
// الدالة الآن تقبل FormData للتعامل مع رفع الملفات
export const createProduct = async (productData: FormData): Promise<Product> => {
    try {
        const response = await api.post<Product>('/admin/products', productData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });
        return response.data;
    } catch (error) {
        console.error("Error creating product:", error);
        throw error;
    }
};

export const updateAdminProduct = async (productId: string, productData: Partial<Product>): Promise<Product> => {
    try {
        const response = await api.put<Product>(`/admin/products/${productId}`, productData);
        return response.data;
    } catch (error) {
        throw error;
    }
};

export const deleteAdminProduct = async (productId: number): Promise<void> => {
    try {
        await api.delete(`/admin/products/${productId}`);
    } catch (error) {
        throw error;
    }
};