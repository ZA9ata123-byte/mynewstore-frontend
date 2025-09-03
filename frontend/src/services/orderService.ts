// Path: src/services/orderService.ts

import api from '@/lib/axiosConfig';
// import { CartItem } from '@/contexts/CartContext'; // الخطوة 1: تم حذف هذا السطر لأنه غير مستخدم

// تعريف شكل بيانات الشحن
interface ShippingInfo {
  name: string;
  address: string;
  city: string;
  phone: string;
}

// تعريف شكل بيانات الطلب التي سنرسلها للخادم
interface OrderPayload {
  shipping_info: ShippingInfo;
  items: { product_id: number; quantity: number; price: number }[];
  total_price: number;
}

// الخطوة 2: إنشاء نوع مخصص للطلب القادم من الخادم
interface Order {
  id: number;
  user_id: number;
  status: string;
  total_price: string;
  shipping_info: ShippingInfo;
  // يمكن إضافة حقول أخرى مثل created_at
}

// تعريف شكل الاستجابة من الخادم بعد إنشاء الطلب
interface OrderResponse {
  message: string;
  order: Order; // الخطوة 3: استخدام النوع الجديد بدلاً من 'any'
}

/**
 * دالة لإرسال طلب جديد إلى الخادم
 */
export const placeOrder = async (payload: OrderPayload): Promise<OrderResponse> => {
  const response = await api.post('/checkout', payload);
  return response.data;
};