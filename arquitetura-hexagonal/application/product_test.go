package application_test

import (
	"go-hexagonal/application"
	"testing"

	"github.com/stretchr/testify/require"

	uuid "github.com/satori/go.uuid"
)

func TestProduct_Enable(t *testing.T) {
	product := application.Product{}
	product.Name = "Product 1"
	product.Price = 10
	product.Status = application.DISABLED

	err := product.Enable()

	require.Nil(t, err)

	product.Price = 0

	err = product.Enable()

	require.Equal(t, "Price must be greater than zero", err.Error())
}

func TestProduct_Disable(t *testing.T) {
	product := application.Product{}
	product.Name = "Product 1"
	product.Price = 10
	product.Status = application.ENABLED

	err := product.Disable()

	require.Equal(t, "Price must be zero", err.Error())

	product.Price = 0

	err = product.Disable()

	require.Nil(t, err)
}

func TestProduct_IsValid(t *testing.T) {
	product := application.Product{}
	product.ID = uuid.NewV4().String()
	product.Name = "Product 1"
	product.Price = 10
	product.Status = application.ENABLED

	isValid, err := product.IsValid()

	require.Nil(t, err)
	require.True(t, isValid)

	product.Status = "invalid"

	isValid, err = product.IsValid()

	require.Equal(t, "Status must be enabled or disabled", err.Error())
	require.False(t, isValid)

	product.Status = application.ENABLED

	product.Price = -10

	isValid, err = product.IsValid()

	require.Equal(t, "Price must be greater or equal to zero", err.Error())
	require.False(t, isValid)
}
