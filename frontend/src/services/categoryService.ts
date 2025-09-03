import axiosInstance from '@/lib/axiosConfig';

// تعريف نوع البيانات للقسم لضمان سلامة الأنواع
// Define the data type for the category to ensure type safety
export interface Category {
  id: number;
  name: string;
  // يمكنك إضافة خصائص أخرى هنا إذا كانت موجودة في الـ API
  // You can add other properties here if they exist in the API
  // مثلاً: description: string;
}

/**
 * دالة لجلب قائمة جميع الأقسام
 * Function to fetch the list of all categories
 * @returns - Promise يحتوي على مصفوفة من الأقسام
 */
export const getCategories = async (): Promise<Category[]> => {
  try {
    // هنا، نفترض أن الـ API endpoint لجلب الأقسام هو /api/categories
    // Here, we assume the API endpoint for fetching categories is /api/categories
    const response = await axiosInstance.get<Category[]>('/categories');
    return response.data;
  } catch (error) {
    // معالجة الأخطاء في حالة فشل جلب البيانات
    // Handle errors in case the data fetching fails
    console.error("Error fetching categories:", error);
    // إرجاع مصفوفة فارغة أو إظهار خطأ مخصص
    // Return an empty array or throw a custom error
    throw new Error('Failed to fetch categories from the server.');
  }
};