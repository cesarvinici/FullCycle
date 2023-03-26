import { app, sequilize } from "../express";
import request from "supertest";
import Product from "../../../domain/product/entity/product";
import ProductFactory from "../../../domain/product/factory/product.factory";
import ProductRepository from "../../product/repository/sequilize/product.repository";


const createProducts = () => {
    const product1 = new Product("123", "Product 1", 10.99);
    const product2 = new Product("456", "Product 2", 20.99);

    const repository = new ProductRepository();
    repository.create(product1);
    repository.create(product2);

    return [product1, product2];
};


describe("E2E test for Product", () => {

    beforeEach(async () => {
        await sequilize.sync({ force: true });
    });

    afterAll(async () => {
        await sequilize.close();
    });


    it("should create a product", async () => {
        const response = await request(app)
            .post("/product")
            .send({
                name: "Product 1",
                price: 10.99,
            });
        

        expect(response.status).toBe(200);
        expect(response.body).toEqual({
            id: expect.any(String),
            name: "Product 1",
            price: 10.99,
        });
    });

    it("should not create a product with empty name", async () => {
        const response = await request(app)
            .post("/product")
            .send({
                name: "",
                price: 10.99,
            });

        expect(response.status).toBe(500);
        expect(response.body).toEqual({
            error: "product: Name is required"
        });
    });

    it("should not create a product with empty price", async () => {
        const response = await request(app)
            .post("/product")
            .send({
                name: "Product 1",
                price: -1,
            });

        expect(response.status).toBe(500);
        expect(response.body).toEqual({
            error: "product: Price must be greater than zero"
        });
    });

    it("should list all products", async () => {
        createProducts();

        const response = await request(app)
            .get("/product");

        expect(response.status).toBe(200);
        expect(response.body).toEqual({
            products: [
                {
                    id: expect.any(String),
                    name: "Product 1",
                    price: 10.99,
                },
                {
                    id: expect.any(String),
                    name: "Product 2",
                    price: 20.99,
                }
            ]
        });
    });

    it("Should find a product by id", async () => {
        const product = createProducts()[0];

        const response = await request(app)
            .get(`/product/${product.id}`);

        expect(response.status).toBe(200);
        expect(response.body).toEqual({
            id: product.id,
            name: product.name,
            price: product.price,
        });
    });

    it("Should not find a product by id", async () => {
        const response = await request(app)
            .get(`/product/123`);

        expect(response.status).toBe(500);
        expect(response.body).toEqual({
            error: "Product not found"
        });
    });

    it("Should update a product", async () => {
        const product = createProducts()[0];

        const response = await request(app)
            .post(`/product/${product.id}`)
            .send({
                name: "Product 1",
                price: 10.99,
            });

        expect(response.status).toBe(200);
        expect(response.body).toEqual({
            id: product.id,
            name: "Product 1",
            price: 10.99,
        });
    });

    it("Should not update a product with empty name", async () => {
        const product = createProducts()[0];

        const response = await request(app)
            .post(`/product/${product.id}`)
            .send({
                name: "",
                price: 10.99,
            });

        expect(response.status).toBe(500);
        expect(response.body).toEqual({
            error: "product: Name is required"
        });
    });

    it("Should not update a product with empty price", async () => {
        const product = createProducts()[0];

        const response = await request(app)
            .post(`/product/${product.id}`)
            .send({
                name: "Product 1",
                price: -1,
            });

        expect(response.status).toBe(500);
        expect(response.body).toEqual({
            error: "product: Price must be greater than zero"
        });
    });
});