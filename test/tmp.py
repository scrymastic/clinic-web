from cryptography.hazmat.backends import default_backend
from cryptography.hazmat.primitives import serialization
from cryptography.hazmat.primitives.asymmetric import rsa
import base64

# Your public key in PEM format
public_key_pem = b"""-----BEGIN PUBLIC KEY-----
MIIBITANBgkqhkiG9w0BAQEFAAOCAQ4AMIIBCQKCAQBzmBqpau3mephLL79gyerM
Kq0rKVgUFKiV+VUTokrl2/+2Jqyl8XZeY501IIvswwM46Ggvg/uk66a35utmhPzq
btRPauDWh7EF0bd1/wwnP5WhsZHrq7B6hoZ6/qLI1r7TzkDptvsoVHNCKpVNWRLu
djmrZ2Be6IhYpMQrMS4L7AgHEWJnBTf0IGqypxDanJzxv0heF5OA6oyzKSoIhW2S
zpZzJK6Lsr3B0CNHVcSQS2S+KcPRFGC9RbaB5PUVQ2nadDyFv8HQMvK93jsrfMCj
sAjOWtLXA3GZOO2R+s3uhisRVvUwyiSwzoVfo4v599ZoYM4gsdCA4v9OrrMdW9/1
AgMBAAE=
-----END PUBLIC KEY-----"""

# Load the public key
public_key = serialization.load_pem_public_key(public_key_pem, backend=default_backend())

# Ensure it's an RSA key
if isinstance(public_key, rsa.RSAPublicKey):
    # Extract the modulus (n) and exponent (e)
    n = public_key.public_numbers().n
    e = public_key.public_numbers().e

    # Print n and e
    print(f"Modulus (n): {n}")
    print(f"Public Exponent (e): {e}")
else:
    print("The provided key is not an RSA public key.")
