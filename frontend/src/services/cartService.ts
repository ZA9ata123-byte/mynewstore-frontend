// Path: frontend/src/services/cartService.ts

const API_URL = 'http://localhost:8000/api'; // <-- تأكد من هذا الرابط

/**
 * دالة لإضافة منتج إلى السلة
 * @param productId - رقم المنتج المُراد إضافته
 * @param quantity - الكمية المطلوبة
 */
export const addToCart = async (productId: number, quantity: number) => {
  const response = await fetch(`${API_URL}/cart/add`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    body: JSON.stringify({
      product_id: productId,
      quantity: quantity,
    }),
  });

  if (!response.ok) {
    // في حالة حدوث خطأ، حاول قراءة رسالة الخطأ من الخادم
    const errorData = await response.json();
    throw new Error(errorData.message || 'فشلت عملية إضافة المنتج إلى السلة');
  }

  return response.json();
};