// Path: src/app/(store)/checkout/page.tsx

"use client";

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { useCart } from '@/contexts/CartContext';
import { useAuth } from '@/contexts/AuthContext';
import Link from 'next/link';
import { placeOrder } from '@/services/orderService';
import { AxiosError } from 'axios';

export default function CheckoutPage() {
  const { cartItems, totalPrice, itemCount, clearCart, isCartLoading } = useCart();
  const { user, isAuthenticated, isLoading: isAuthLoading } = useAuth();
  const router = useRouter();

  const [shippingInfo, setShippingInfo] = useState({
    name: '', email: '', address: '', city: '', phone: '',
  });
  const [createAccount, setCreateAccount] = useState(false);
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false); // سيتم استخدامه
  const [error, setError] = useState<string | null>(null); // سيتم استخدامه

  useEffect(() => {
    if (isAuthenticated && user) {
      setShippingInfo(prev => ({ ...prev, name: user.name, email: user.email }));
    }
  }, [isAuthenticated, user]);

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => { // سيتم استخدامه
    const { name, value } = e.target;
    setShippingInfo(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmitOrder = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    const orderPayload = {
      shipping_info: shippingInfo,
      items: cartItems.map(item => ({ product_id: item.id, quantity: item.quantity, price: parseFloat(item.price) })),
      total_price: totalPrice,
      create_account: !isAuthenticated && createAccount,
      password: !isAuthenticated && createAccount ? password : undefined,
    };

    try {
      await placeOrder(orderPayload);
      clearCart();
      router.push('/order-success');
    } catch (err) {
      let errorMessage = "حدث خطأ أثناء إرسال الطلب.";
      if (err instanceof AxiosError && err.response) {
        if (err.response.data.errors) {
            errorMessage = Object.values(err.response.data.errors).flat().join(' ');
        } else {
            errorMessage = err.response.data.message || errorMessage;
        }
      }
      setError(errorMessage);
    } finally {
      setLoading(false);
    }
  };

  if (isAuthLoading || isCartLoading) {
    return <div className="text-center py-16">جاري التحميل...</div>;
  }
  if (itemCount === 0 && !isCartLoading) {
    return (
      <div className="text-center py-16"><p className="text-xl text-gray-500">سلة مشترياتك فارغة.</p><Link href="/" className="mt-6 inline-block bg-blue-500 text-white px-8 py-3 rounded-full font-semibold hover:bg-blue-600">العودة للتسوق</Link></div>
    );
  }
  
  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold mb-8 text-center">إتمام الشراء</h1>
      {/* النموذج يحيط بكل شيء ليتمكن الزر من إرساله */}
      <form onSubmit={handleSubmitOrder}>
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-12">
          <div className="lg:col-span-2 bg-white p-8 rounded-lg shadow-md">
            <h2 className="text-2xl font-semibold mb-6">معلومات الشحن</h2>
            <div className="space-y-6">
              {/* كل حقل الآن مربوط بدالة handleInputChange */}
              <div><label htmlFor="name" className="block text-sm font-medium">الاسم الكامل</label><input type="text" name="name" id="name" required value={shippingInfo.name} onChange={handleInputChange} className="mt-1 block w-full p-2 border rounded-md"/></div>
              <div><label htmlFor="email" className="block text-sm font-medium">البريد الإلكتروني</label><input type="email" name="email" id="email" required readOnly={isAuthenticated} value={shippingInfo.email} onChange={handleInputChange} className="mt-1 block w-full p-2 border rounded-md bg-gray-50"/></div>
              <div><label htmlFor="address" className="block text-sm font-medium">العنوان</label><input type="text" name="address" id="address" required placeholder="اسم الشارع، رقم المنزل" value={shippingInfo.address} onChange={handleInputChange} className="mt-1 block w-full p-2 border rounded-md"/></div>
              <div><label htmlFor="city" className="block text-sm font--medium">المدينة</label><input type="text" name="city" id="city" required value={shippingInfo.city} onChange={handleInputChange} className="mt-1 block w-full p-2 border rounded-md"/></div>
              <div><label htmlFor="phone" className="block text-sm font-medium">رقم الهاتف</label><input type="tel" name="phone" id="phone" required value={shippingInfo.phone} onChange={handleInputChange} className="mt-1 block w-full p-2 border rounded-md"/></div>
              {!isAuthenticated && (
                <div className="space-y-4 rounded-md bg-gray-50 p-4">
                  <div className="flex items-center"><input id="createAccount" name="createAccount" type="checkbox" checked={createAccount} onChange={(e) => setCreateAccount(e.target.checked)} className="h-4 w-4 rounded"/><label htmlFor="createAccount" className="ml-3">هل تريد إنشاء حساب؟ (اختياري)</label></div>
                  {createAccount && (
                    <div><label htmlFor="password">اختر كلمة مرور</label><input type="password" name="password" id="password" required={createAccount} value={password} onChange={(e) => setPassword(e.target.value)} className="mt-1 block w-full p-2 border rounded-md" placeholder="كلمة مرور قوية"/></div>
                  )}
                </div>
              )}
            </div>
          </div>
          <div className="bg-white p-6 rounded-lg shadow-md h-fit">
            <h2 className="text-xl font-semibold border-b pb-4 mb-4">ملخص طلبك</h2>
            {cartItems.map(item => (<div key={item.id} className="flex justify-between text-sm mb-2"><span>{item.name} x {item.quantity}</span><span>{(parseFloat(item.price) * item.quantity).toFixed(2)} د.م.</span></div>))}
            <div className="border-t mt-4 pt-4 flex justify-between font-bold text-lg"><span>المجموع الإجمالي</span><span>{totalPrice.toFixed(2)} د.م.</span></div>
            
            {/* هنا يتم استخدام 'error' */}
            {error && <p className="text-sm text-center text-red-600 mt-4 p-2 bg-red-50 rounded-md">{error}</p>}
            
            {/* الزر الآن من نوع 'submit' ويستخدم 'loading' */}
            <button type="submit" disabled={loading} className="mt-6 w-full text-center bg-green-500 text-white py-3 rounded-lg font-semibold hover:bg-green-600 disabled:bg-gray-400">
              {loading ? 'جاري تأكيد الطلب...' : 'تأكيد الطلب'}
            </button>
          </div>
        </div>
      </form>
    </div>
  );
}