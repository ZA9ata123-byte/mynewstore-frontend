"use client";

import Link from 'next/link';
import { Product } from '@/services/productService';
import { useCart } from '@/contexts/CartContext';

export default function ProductCard({ product }: { product: Product }) {
  const { addToCart } = useCart();

  const handleAddToCart = () => {
    addToCart(product);
  };

  return (
    <div className="border rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300 flex flex-col">
      <Link href={`/products/${product.id}`} className="block">
        <div className="bg-gray-200 h-48 flex items-center justify-center">
          <span className="text-gray-500">صورة المنتج</span>
        </div>
      </Link>
      
      <div className="p-4 flex flex-col flex-grow">
        <h3 className="text-lg font-semibold text-gray-800 truncate">
          {product.name}
        </h3>
        <p className="text-sm text-gray-600 mt-1 flex-grow">
          {product.description.substring(0, 70)}...
        </p>
        
        <div className="mt-4 flex items-center justify-between">
          {/* --- هنا تم التعديل: منطق عرض السعر الذكي --- */}
          <div className="text-xl font-bold text-gray-900">
            {product.variants_count > 0 && (
              <span className="text-sm font-normal text-gray-500">ابتداءً من </span>
            )}
            {product.price} د.م.
          </div>
          
          {/* --- المنطق الذكي لعرض الزر المناسب --- */}
          {product.variants_count > 0 ? (
            <Link 
              href={`/products/${product.id}`}
              className="px-4 py-2 text-sm font-semibold bg-gray-700 text-white rounded-full hover:bg-gray-800 transition-colors"
            >
              اختر الخيارات
            </Link>
          ) : (
            <button 
              onClick={handleAddToCart}
              className="px-4 py-2 text-sm font-semibold bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors"
            >
              أضف إلى السلة
            </button>
          )}
        </div>
      </div>
    </div>
  );
}