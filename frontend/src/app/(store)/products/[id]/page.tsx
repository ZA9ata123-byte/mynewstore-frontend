// Path: src/app/(store)/products/[id]/page.tsx

"use client";

import { useEffect, useState, use } from 'react';
import { useRouter } from 'next/navigation';
import { getProductById, Product, ProductVariant } from '@/services/productService';
import { useCart } from '@/contexts/CartContext';

export default function ProductDetailsPage({ params }: { params: Promise<{ id: string }> }) {
  const { id } = use(params);
  const router = useRouter();
  const { addToCart } = useCart();
  
  const [product, setProduct] = useState<Product | null>(null);
  const [selectedVariant, setSelectedVariant] = useState<ProductVariant | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const fetchProductDetails = async () => {
      try {
        const productData = await getProductById(id);
        setProduct(productData);
        if (productData.variants && productData.variants.length > 0) {
          setSelectedVariant(productData.variants[0]);
        }
      } catch (error) { console.error(error); } finally { setLoading(false); }
    };
    fetchProductDetails();
  }, [id]);

  const handleAddToCart = () => {
    if (!product) return;
    if (product.variants && product.variants.length > 0 && !selectedVariant) {
        alert("الرجاء اختيار أحد الخيارات المتاحة.");
        return;
    }
    const itemToAdd = selectedVariant ? { ...product, ...selectedVariant, id: selectedVariant.id } : product;
    addToCart(itemToAdd);
  };

  const handleBuyNow = () => {
    if (!product) return;
    if (product.variants && product.variants.length > 0 && !selectedVariant) {
        alert("الرجاء اختيار أحد الخيارات المتاحة أولاً.");
        return;
    }
    const itemToAdd = selectedVariant ? { ...product, ...selectedVariant, id: selectedVariant.id } : product;
    addToCart(itemToAdd);
    router.push('/checkout');
  };

  if (loading) return <p className="text-center py-12">جاري التحميل...</p>;
  if (!product) return <p className="text-center py-12">لم يتم العثور على المنتج.</p>;

  // هذا هو المتغير الذي كان عليه التنبيه
  const displayPrice = selectedVariant ? selectedVariant.price : product.price;

  return (
    <div className="bg-white">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-2 gap-12">
          <div className="bg-gray-100 rounded-lg flex items-center justify-center h-96">
            <span className="text-gray-500">صورة المنتج</span>
          </div>
          <div className="flex flex-col justify-center">
            <h1 className="text-3xl md:text-4xl font-bold text-gray-900">{product.name}</h1>
            <p className="mt-4 text-lg text-gray-600">{product.description}</p>

            {product.variants && product.variants.length > 0 && (
              <div className="mt-6">
                <h2 className="text-md font-medium text-gray-900">اختر الخيار:</h2>
                <div className="mt-3 flex flex-wrap gap-3">
                  {product.variants.map((variant) => (
                    <button
                      key={variant.id}
                      onClick={() => setSelectedVariant(variant)}
                      className={`px-5 py-2 border rounded-full text-sm font-semibold transition-colors ${selectedVariant?.id === variant.id ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-800 hover:bg-gray-100'}`}
                    >
                      {variant.name}
                    </button>
                  ))}
                </div>
              </div>
            )}
            
            <div className="mt-8">
              {/* --- هنا تم التصحيح: استخدمنا displayPrice --- */}
              <span className="text-4xl font-bold text-gray-900">{displayPrice} د.م.</span>
            </div>

            <div className="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
              <button
                onClick={handleAddToCart}
                className="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold text-lg hover:bg-blue-700 transition-colors disabled:bg-gray-400"
                disabled={product.variants && product.variants.length > 0 && !selectedVariant}
              >
                أضف إلى السلة
              </button>
              <button
                onClick={handleBuyNow}
                className="w-full bg-green-500 text-white py-3 rounded-lg font-semibold text-lg text-center hover:bg-green-600 transition-colors"
              >
                اشترِ الآن
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}