import AggregateRoot from "../../@shared/domain/entity/aggregate-root.interface";
import BaseEntity from "../../@shared/domain/entity/base.entity";
import Id from "../../@shared/domain/value-object/id.value-object";
import Product from "./Product.entity";
import { Client } from "./client.entity";

type OrderProps = {
    id?: Id;
    client: Client;
    products: Product[];
    status?: string
}


export default class Order extends BaseEntity implements AggregateRoot {

    private _client: Client;
    private _products: Product[];
    private _status: string;

    constructor(props: OrderProps) {
        super(props.id);
        this._client = props.client;
        this._products = props.products;
        this._status = props.status || 'pending';
    }

    get client(): Client {
        return this._client;
    }

    get products(): Product[] {
        return this._products;
    }

    get status(): string {
        return this._status;
    }

    approve(): void {
        this._status = 'approved';
    }

    get total(): number {
        return this._products.reduce(
            (total, product) => total + product.salesPrice, 0
        );
    }

}