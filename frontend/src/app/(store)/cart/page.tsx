// Path: src/app/(store)/cart/page.tsx

"use client";

import { useCart } from "@/contexts/CartContext";
import Link from "next/link";

export default function CartPage() {
  const { cartItems, removeFromCart, updateQuantity, itemCount, totalPrice } = useCart();

  return (
    <div className="container mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold mb-8 text-center">سلة المشتريات</h1>
      
      {itemCount === 0 ? (
        <div className="text-center py-16">
          <p className="text-xl text-gray-500">سلة المشتريات فارغة.</p>
          <Link href="/" className="mt-6 inline-block bg-blue-500 text-white px-8 py-3 rounded-full font-semibold hover:bg-blue-600">
            ابدأ التسوق
          </Link>
        </div>
      ) : (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* قائمة المنتجات في السلة */}
          <div className="lg:col-span-2 bg-white p-6 rounded-lg shadow-md">
            {cartItems.map((item) => (
              <div key={item.id} className="flex items-center justify-between border-b py-4">
                <div className="flex items-center gap-4">
                  <div className="w-20 h-20 bg-gray-200 rounded-md flex items-center justify-center">
                    {/* صورة المنتج */}
                  </div>
                  <div>
                    <h2 className="font-semibold text-lg">{item.name}</h2>
                    <p className="text-gray-600">{item.price} د.م.</p>
                  </div>
                </div>
                <div className="flex items-center gap-4">
                  <div className="flex items-center border rounded-md">
                    <button onClick={() => updateQuantity(item.id, item.quantity - 1)} className="px-3 py-1 text-lg">-</button>
                    <span className="px-4 py-1">{item.quantity}</span>
                    <button onClick={() => updateQuantity(item.id, item.quantity + 1)} className="px-3 py-1 text-lg">+</button>
                  </div>
                  <button onClick={() => removeFromCart(item.id)} className="text-red-500 hover:text-red-700">
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </div>
            ))}
          </div>

          {/* ملخص الطلب */}
          <div className="bg-white p-6 rounded-lg shadow-md h-fit">
            <h2 className="text-xl font-semibold border-b pb-4">ملخص الطلب</h2>
            <div className="flex justify-between items-center my-4">
              <span className="text-gray-600">المجموع الفرعي ({itemCount} منتجات)</span>
              <span className="font-semibold">{totalPrice.toFixed(2)} د.م.</span>
            </div>
            <div className="flex justify-between items-center my-4">
              <span className="text-gray-600">الشحن</span>
              <span className="font-semibold">مجاني</span>
            </div>
            <div className="border-t mt-4 pt-4 flex justify-between items-center font-bold text-lg">
              <span>المجموع الإجمالي</span>
              <span>{totalPrice.toFixed(2)} د.م.</span>
            </div>
            <Link href="/checkout" className="mt-6 block w-full text-center bg-green-500 text-white py-3 rounded-lg font-semibold hover:bg-green-600">
              الانتقال إلى الدفع
            </Link>
          </div>
        </div>
      )}
    </div>
  );
}