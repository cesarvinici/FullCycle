import Order from "../entity/order";
import OrderItem from "../entity/order_items";

interface OrderFactoryProps {
    id: string;
    customerId: string;
    items: Array<{
        id: string;
        name: string;
        price: number;
        quantity: number;
        ProductId: string;
    }>;
}


export default class OrderFactory {
    static create(props: OrderFactoryProps): Order {
        const items = props.items.map(item => {
            return new OrderItem(
                item.id,
                item.name,
                item.price,
                item.ProductId,
                item.quantity,
            );
        });
        return new Order(props.id, props.customerId, items);
    }
}