import api from '@/lib/axiosConfig';
import { Paginator, Category } from '@/types'; // ✅ دابا هاد السطر خدام مزيان

/**
 * Fetches all categories for the admin dashboard.
 */
export const getAdminCategories = async (): Promise<Paginator<Category>> => {
  try {
    const response = await api.get<Paginator<Category>>('/admin/categories');
    return response.data;
  } catch (error) {
    console.error('Failed to fetch admin categories:', error);
    throw error;
  }
};