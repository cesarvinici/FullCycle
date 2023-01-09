FROM golang as builder

WORKDIR /var/www
COPY . .
RUN go build hello.go

FROM scratch
WORKDIR /var/www
COPY --from=builder /var/www/hello .

CMD ["./hello"]
