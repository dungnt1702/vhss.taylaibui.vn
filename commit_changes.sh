#!/bin/bash

# Script để commit các thay đổi hiện tại

echo "🔄 Đang kiểm tra trạng thái git..."
git status

echo ""
echo "📝 Đang thêm tất cả thay đổi..."
git add .

echo ""
echo "💾 Đang commit với message mô tả..."
git commit -m "feat: Cải thiện navigation và footer

- Thêm tiêu đề 'TAY LÁI BỤI SÓC SƠN' trên mobile
- Căn giữa tiêu đề mobile  
- Navigation sticky với z-index cao
- Footer đơn giản với copyright và số điện thoại
- Menu mobile trượt từ trái với overlay
- Sửa z-index overlay để không che menu"

echo ""
echo "✅ Commit thành công!"
echo "📊 Trạng thái sau commit:"
git status

echo ""
echo "🚀 Bạn có muốn push lên remote không? (y/n)"
read -r response
if [[ "$response" =~ ^[Yy]$ ]]; then
    echo "📤 Đang push lên remote..."
    git push origin main
    echo "✅ Push thành công!"
else
    echo "⏭️ Bỏ qua push"
fi

echo ""
echo "🎉 Hoàn thành!"
