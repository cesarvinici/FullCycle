import { Column, Model, PrimaryKey, Table } from "sequelize-typescript";

@Table({
    tableName: 'invoice',
    timestamps: false
})
export default class InoviceModel extends Model {

    @PrimaryKey
    @Column({allowNull: false})
    id: string;

    @Column({allowNull: false})
    name: string;

    @Column({allowNull: false})
    document:string;

    @Column({allowNull: false})
    street: string;

    @Column({allowNull: false})
    number: number;

    @Column({allowNull: false})
    complement: string;

    @Column({allowNull: false})
    city: string;

    @Column({allowNull: false})
    state: string;

    @Column({allowNull: false, field: 'zip_code'})
    zipCode: string;

    @Column({allowNull: false})
    items: string;

    @Column({allowNull: false})
    total: number;

} 