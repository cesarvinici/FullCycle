import { Sequelize } from "sequelize-typescript";
import Address from "../../domain/entity/address";
import Customer from "../../domain/entity/customer";
import Order from "../../domain/entity/order";
import OrderItem from "../../domain/entity/order_items";
import Product from "../../domain/entity/product";
import CustomerModel from "../db/sequelize/model/customer.model";
import OrderItemModel from "../db/sequelize/model/order-item.model";
import OrderModel from "../db/sequelize/model/order.model";
import ProductModel from "../db/sequelize/model/product.model";
import CustomerRepository from "./customer.repository";
import OrderRepository from "./order.repository";
import ProductRepository from "./product.repository";

describe("Order repository test", () => {

    let sequilize: Sequelize;

    beforeEach(async () => {
        sequilize = new Sequelize({
            dialect: "sqlite",
            storage: ":memory:",
            logging: false,
            sync: { force: true },
        });

        sequilize.addModels(
            [OrderModel, OrderItemModel, CustomerModel, ProductModel]
        );
        await sequilize.sync();
    });
    afterEach(async () => {
        await sequilize.close();
    });

    it("should create order", async () => {

        const customerRepository = new CustomerRepository();        
        const customer = new Customer("1", "customer 1");
        const address = new Address("street 1", 1, "city 1", "state 1", "zip 1");
        customer.changeAddress(address);
        await customerRepository.create(customer);

        const productRepository = new ProductRepository();
        const product = new Product("1", "product 1", 10);
        await productRepository.create(product);

        const orderItem = new OrderItem(
            "1",
            product.name,
            product.price,
            product.id,
            2
        );

        const orderRepository = new OrderRepository();
        const order = new Order("1", customer.id, [orderItem]);
        await orderRepository.create(order);

        const orderFound = await OrderModel.findOne({
            where: { id: order.id },
            include: ["items"]
        })

        expect(orderFound.toJSON()).toStrictEqual({
            id: "1",
            customer_id: "1",
            total: order.total(),
            items: [
                {
                    id: orderItem.id,
                    order_id: order.id,
                    product_id: orderItem.productId,
                    name: orderItem.name,
                    price: orderItem.price,
                    quantity: orderItem.quantity
                }
            ]
        });
    })


    it("should find order", async () => {
        const customerRepository = new CustomerRepository();        
        const customer = new Customer("1", "customer 1");
        const address = new Address("street 1", 1, "city 1", "state 1", "zip 1");
        customer.changeAddress(address);
        await customerRepository.create(customer);

        const productRepository = new ProductRepository();
        const product = new Product("1", "product 1", 10);
        await productRepository.create(product);

        const orderItem = new OrderItem(
            "1",
            product.name,
            product.price,
            product.id,
            2
        );

        const order = new Order("1", customer.id, [orderItem]);

        await OrderModel.create({
            id: order.id,
            customer_id: order.customerId,
            total: order.total(),
            items: order.items.map(item => ({
                id: item.id,
                product_id: item.productId,
                quantity: item.quantity,
                name: item.name,
                price: item.price,
            })),
        }, 
        {
            include: [{model: OrderItemModel}],

        });

        const orderRepository = new OrderRepository();
        
        const orderFound = await orderRepository.find(order.id);

        expect(orderFound).toStrictEqual(order);
    });



    it("should update order", async () => {

        const customerRepository = new CustomerRepository();        
        const customer = new Customer("1", "customer 1");
        const address = new Address("street 1", 1, "city 1", "state 1", "zip 1");
        customer.changeAddress(address);
        await customerRepository.create(customer);

        const productRepository = new ProductRepository();
        const product = new Product("1", "product 1", 10);
        await productRepository.create(product);

        const orderItem = new OrderItem(
            "1",
            product.name,
            product.price,
            product.id,
            2
        );

        const orderRepository = new OrderRepository();
        const order = new Order("1", customer.id, [orderItem]);
        await orderRepository.create(order);

        const orderFound = await OrderModel.findOne({
            where: { id: order.id },
            include: ["items"]
        });


        const product2 = new Product("2", "product 2", 20);
        await productRepository.create(product2);

        const orderItem2 = new OrderItem(
            "2",
            product2.name,
            product2.price,
            product2.id,
            3
        );

        order.addItem(orderItem2);
        await orderRepository.update(order);

        const orderFound2 = await OrderModel.findOne({
            where: { id: order.id },
            include: ["items"]
        });

        expect(orderFound2.items.length).toBe(2);
        expect(orderFound2.total).toBe(80);
    });

    it("should delete Order Item", async () => {

        const customerRepository = new CustomerRepository();        
        const customer = new Customer("1", "customer 1");
        const address = new Address("street 1", 1, "city 1", "state 1", "zip 1");
        customer.changeAddress(address);
        await customerRepository.create(customer);

        const productRepository = new ProductRepository();
        const product = new Product("1", "product 1", 10);
        await productRepository.create(product);

        const product2 = new Product("2", "product 2", 20);
        await productRepository.create(product2);

        const orderItem = new OrderItem(
            "1",
            product.name,
            product.price,
            product.id,
            2
        );

        const orderItem2 = new OrderItem(
            "2",
            product2.name,
            product2.price,
            product2.id,
            3
        );

        const orderRepository = new OrderRepository();
        const order = new Order("1", customer.id, [orderItem, orderItem2]);
        await orderRepository.create(order);

        const orderFound = await orderRepository.find(order.id);

        order.removeItem(orderItem2.id);

        await orderRepository.update(order);

        const orderFound2 = await orderRepository.find(order.id);
        expect(orderFound2.items.length).toBe(1);
    });


    it("Should find all orders", async () => {
        const customerRepository = new CustomerRepository();        
        const customer = new Customer("1", "customer 1");
        const address = new Address("street 1", 1, "city 1", "state 1", "zip 1");
        customer.changeAddress(address);
        await customerRepository.create(customer);

        const productRepository = new ProductRepository();
        const product = new Product("1", "product 1", 10);
        await productRepository.create(product);

        const product2 = new Product("2", "product 2", 20);
        await productRepository.create(product2);

        const orderItem = new OrderItem(
            "1",
            product.name,
            product.price,
            product.id,
            2
        );

        const orderRepository = new OrderRepository();
        const order = new Order("1", customer.id, [orderItem]);
        await orderRepository.create(order);

        const orderItem2 = new OrderItem(
            "2",
            product2.name,
            product2.price,
            product2.id,
            3
        );

        const order2 = new Order("2", customer.id, [orderItem2]);
        await orderRepository.create(order2);

        const orders = await orderRepository.findAll();

        expect(orders.length).toBe(2);
    })

});