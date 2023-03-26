import { app, sequilize } from "../express";
import request from "supertest";
import CustomerRepository from "../../customer/repository/sequilize/customer.repository";
import CustomerFactory from "../../../domain/customer/factory/customer.factory";
import Address from "../../../domain/customer/value-object/address";

const createCustomers = () => {
    const repository = new CustomerRepository();
    const customer1 = CustomerFactory.createWithAddress("John", new Address(
        "Main Street",
        123,
        "New York",
        "NY",
        "10001"
    ));

    const customer2 = CustomerFactory.createWithAddress("Mary", new Address(
        "Main Street",
        123,
        "New York",
        "NY",
        "10001"
    ));

    repository.create(customer1);
    repository.create(customer2);

    return [customer1, customer2];

};


describe("E2E test for Customer", () => {

    
    beforeEach(async () => {
        await sequilize.sync({ force: true });
    });

    afterAll(async () => {
        await sequilize.close();
    });

    it("should create a customer", async () => {
        const response = await request(app)
            .post("/customer")
            .send({
                name: "John",
                address: {
                    street: "Main Street",
                    number: 123,
                    city: "New York",
                    state: "NY",
                    zip: "10001"
                }
            });

        expect(response.status).toBe(200);
        expect(response.body).toEqual({
            id: expect.any(String),
            name: "John",
            address: {
                street: "Main Street",
                number: 123,
                city: "New York",
                state: "NY",
                zip: "10001"
            },
        });
    });

    it("should not create a customer with empty name", async () => {
        const response = await request(app)
            .post("/customer")
            .send({
                name: "",
                address: {
                    street: "Main Street",
                    number: 123,
                    city: "New York",
                    state: "NY",
                    zip: "10001"
                }
            });

        expect(response.status).toBe(500);
        expect(response.body).toEqual({
            error: "customer: Name is required"
        });
    });


    it("Should list all customers", async () => {       
        createCustomers();

        const response = await request(app)
            .get("/customer");

        expect(response.status).toBe(200);
        expect(response.body).toEqual({
            customers: [
                {
                    id: expect.any(String),
                    name: "John",
                    address: {
                        street: "Main Street",
                        number: 123,
                        city: "New York",
                        state: "NY",
                        zip: "10001"
                    },
                },
                {
                    id: expect.any(String),
                    name: "Mary",
                    address: {
                        street: "Main Street",
                        number: 123,
                        city: "New York",
                        state: "NY",
                        zip: "10001"
                    },
                }
            ]
        })
    });

    it("Should find a customer", async () => {
        
        const customer = await createCustomers()[0];

        const response = await request(app)
            .get(`/customer/${customer.id}`)

        expect(response.status).toBe(200);
        expect(response.body).toEqual({
            id: customer.id,
            name: "John",
            address: {
                street: "Main Street",
                number: 123,
                city: "New York",
                state: "NY",
                zip: "10001"
            },
        });
    });

    it("Should not find a customer", async () => {
        const response = await request(app)
            .get(`/customer/123`)

        expect(response.status).toBe(500);
        expect(response.body).toEqual({
            error: "Customer not found"
        });
    });

    it("Should update a customer", async () => {
        const customer = await createCustomers()[0];

        const response = await request(app)
            .post(`/customer/${customer.id}`)
            .send({
                name: "Mary",
                address: {
                    street: "Main Street",
                    number: 123,
                    city: "New York",
                    state: "NY",
                    zip: "10001"
                }
            });

        expect(response.status).toBe(200);
        expect(response.body).toEqual({
            id: customer.id,
            name: "Mary",
            address: {
                street: "Main Street",
                number: 123,
                city: "New York",
                state: "NY",
                zip: "10001"
            },
        });
    });
})