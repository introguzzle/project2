@extends('nav.nav')
@section('content')
    <style>
        .admin-panel {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }

        .admin-panel-item {
            background-color: #fff;
            padding: 20px;
            margin: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: calc(33.33% - 20px);
            max-width: 300px;
        }

        .admin-panel-item h2 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        .admin-panel-item form {
            display: flex;
            flex-direction: column;
        }

        .admin-panel-item label {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .admin-panel-item input[type="text"],
        .admin-panel-item input[type="number"],
        .admin-panel-item textarea {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .admin-panel-item textarea {
            resize: vertical;
            min-height: 100px;
        }

        .admin-panel-item button {
            padding: 10px 20px;
            background-color: #e6342a;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 18px;
        }

        .admin-panel-item button:hover {
            background-color: #c70000;
        }
    </style>

    <div class="admin-panel">
        <div class="admin-panel-item">
            <h2>Add Product</h2>
            <form action="">
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" required>

                <label for="product_price">Price:</label>
                <input type="number" id="product_price" name="product_price" required>

                <label for="product_description">Description:</label>
                <textarea id="product_description" name="product_description" required></textarea>

                <button type="submit">Add Product</button>
            </form>
        </div>

        <div class="admin-panel-item">
            <h2>Update Product</h2>
            <form action="">
                <label for="update_product">Select Product:</label>
                <select id="update_product" name="update_product">
                    <option value="product1">Product 1</option>
                    <option value="product2">Product 2</option>
                    <option value="product3">Product 3</option>
                </select>

                <label for="new_product_name">New Product Name:</label>
                <input type="text" id="new_product_name" name="new_product_name">

                <label for="new_product_price">New Price:</label>
                <input type="number" id="new_product_price" name="new_product_price">

                <label for="new_product_description">New Description:</label>
                <textarea id="new_product_description" name="new_product_description"></textarea>

                <button type="submit">Update Product</button>
            </form>
        </div>

        <div class="admin-panel-item">
            <h2>Delete Product</h2>
            <form action="">
                <label for="delete_product">Select Product:</label>
                <select id="delete_product" name="delete_product">
                    <option value="product1">Product 1</option>
                    <option value="product2">Product 2</option>
                    <option value="product3">Product 3</option>
                </select>

                <button type="submit">Delete Product</button>
            </form>
        </div>
    </div>
@endsection
