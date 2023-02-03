import Address from "../../domain/entity/address";
import Customer from "../../domain/entity/customer";
import Order from "../../domain/entity/order";
import CustomerRepositoryInterface from "../../domain/repository/customer-repository.interface";
import OrderRepositoryInterface from "../../domain/repository/order.repository.interface";
import CustomerModel from "../db/sequelize/model/customer.model";
import OrderItemModel from "../db/sequelize/model/order-item.model";
import OrderModel from "../db/sequelize/model/order.model";
import ProductModel from "../db/sequelize/model/product.model";
import OrderItem from "../../domain/entity/order_items";

export default class OrderRepository implements OrderRepositoryInterface {
   

    async create(entity: Order): Promise<void> {
       
        await OrderModel.create({
            id: entity.id,
            customer_id: entity.customerId,
            total: entity.total(),
            items: entity.items.map(item => ({
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
    }

    async update(entity: Order): Promise<void> {
        const items = entity.items;
        
        const foundOrder = await this.find(entity.id);
        const foundItems = foundOrder.items;

        const existingItems = items.filter(item => foundItems.find(i => i.id === item.id));
        const newItems = items.filter(item => !foundItems.find(i => i.id === item.id));
        const removedItems = foundItems.filter(item => !items.find(i => i.id === item.id));

        if (removedItems.length > 0) {
            await OrderItemModel.destroy({
                where: {
                    id: removedItems.map(item => item.id),
                }
            });
        }
        
        if (newItems.length > 0) {
            await OrderItemModel.bulkCreate(newItems.map(item => ({
                id: item.id,
                order_id: entity.id,
                product_id: item.productId,
                name: item.name,
                price: item.price,
                quantity: item.quantity,
            })));
        }

        if (existingItems.length > 0) {
            await Promise.all(existingItems.map(async item => {
                const foundItem = foundItems.find(i => i.id === item.id);
                if (foundItem) {
                    await OrderItemModel.update({
                        quantity: item.quantity,
                    }, {
                        where: {
                            id: item.id,
                        }
                    });
                }
            }));
        }

        await OrderModel.update({
            total: entity.total(),
        }, {
            where: {
                id: entity.id,
            }
        });
        
       
    }
    
    async find(orderId: string): Promise<Order> {
        
        const orderFound = await OrderModel.findOne({
            where: {id: orderId},
            include: [
                {
                    model: OrderItemModel,
                    as: "items",
                },
            ],
        });

        if (!orderFound) {
            throw new Error("Order not found");
        }

        const orderItems = orderFound.items.map(item => new OrderItem(
            item.id,
            item.name,
            item.price,
            item.product_id,
            item.quantity
        ));


        const order = new Order(
            orderFound.id,
            orderFound.customer_id,
            orderItems
        );

        return order;       

    }
    async findAll(): Promise<Order[]> {
        
        const ordersFound = await OrderModel.findAll({
            include: [
                {
                    model: OrderItemModel,
                    as: "items",
                },
            ],
        });

        const orders = ordersFound.map(orderFound => {
            const orderItems = orderFound.items.map(item => new OrderItem(
                item.id,
                item.name,
                item.price,
                item.product_id,
                item.quantity
            ));
    
            const order = new Order(
                orderFound.id,
                orderFound.customer_id,
                orderItems
            );
    
            return order;
        });

        return orders;

    }
}