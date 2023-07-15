import AggregateRoot from "../../@shared/domain/entity/aggregate-root.interface";
import BaseEntity from "../../@shared/domain/entity/base.entity";
import Id from "../../@shared/domain/value-object/id.value-object"

type TransactionProps = {
    id?: Id;
    amount: number;
    orderId: string;
    status?: string;
    updated_at?: Date;
    created_at?: Date;
}


export default class Transaction extends BaseEntity  implements AggregateRoot {

    private _amount: number;
    private _orderId: string;
    private _status: string;

    constructor(props: TransactionProps) {
        super(props.id);
        this._amount = props.amount;
        this._orderId = props.orderId;
        this._status = props.status || 'pending';
    }

    get amount(): number {
        return this._amount;
    }

    get orderId(): string {
        return this._orderId;
    }

    get status(): string {
        return this._status;
    }

    validate(): void {
        if (this._amount <= 0) {
            throw new Error('Invalid amount');
        }
    }

    approve(): void {
        this._status = 'approved';
    }

    decline(): void {
        this._status = 'declined';
    }


    process(): void {
        if (this._amount >= 100) {
            this.approve();
            return;
        }

        this.decline();
    }


}