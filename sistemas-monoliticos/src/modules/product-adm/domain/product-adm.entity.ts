import AggregateRoot from "../../@shared/domain/entity/aggregate-root.interface";
import BaseEntity from "../../@shared/domain/entity/base.entity";
import Id from "../../@shared/domain/value-object/id.value-object";

type ProductAdmProps = {
    id?: Id;
    name: string;
    description: string;
    purchasePrice: number;
    stock: number;
};

export default class ProductAdm extends BaseEntity implements AggregateRoot {

    private _name: string;
    private _description: string;
    private _purchasePrice: number;
    private _stock: number;

    constructor(props: ProductAdmProps) {
        super(props.id);
        this._name = props.name;
        this._description = props.description;
        this._purchasePrice = props.purchasePrice;
        this._stock = props.stock;
    }

    get name(): string {
        return this._name;
    }

    get description(): string {
        return this._description;
    }

    get purchasePrice(): number {
        return this._purchasePrice;
    }

    get salesPrice(): number {
        return this._purchasePrice * 1.2;
    }

    get stock(): number {
        return this._stock;
    }

    set stock(stock: number) {
        this._stock = stock;
    }

    set description(description: string) {
        this._description = description;
    }

    set purchasePrice(purchasePrice: number) {
        this._purchasePrice = purchasePrice;
    }    
}