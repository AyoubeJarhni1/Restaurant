import numpy as np
import matplotlib.pyplot as plt
from scipy.optimize import linprog

# Coefficients de la fonction objective
c1, c2 = 10, 20

# Définition des contraintes sous forme d'inéquations
a1, b1, d1 = 1, 2, 8     # x1 + 2x2 <= 8
a2, b2, d2 = 1, 0, 10    # x1 <= 10
a3, b3, d3 = 2, -1, 20   # 2x1 - x2 <= 20

# Définir les contraintes dans un format utilisable pour scipy
A = [[-a1, -b1], [-a2, -b2], [-a3, -b3]]
b = [-d1, -d2, -d3]

# Limites pour x1 et x2
x1 = np.linspace(0, 15, 200)

# Tracer les contraintes
plt.plot(x1, (d1 - a1 * x1) / b1, label=r'$x_1 + 2x_2 \leq 8$')
plt.plot(x1, (d2 - a2 * x1) / b2, label=r'$x_1 \leq 10$')
plt.plot(x1, (d3 - a3 * x1) / b3, label=r'$2x_1 - x_2 \leq 20$')

# Ajouter les limites x1, x2 >= 0
plt.xlim((0, 15))
plt.ylim((0, 15))
plt.xlabel(r'$x_1$')
plt.ylabel(r'$x_2$')

# Remplir la région de faisabilité
x = np.array([0, 10, 8, 0])  # Coordonnées des sommets dans x1
y = np.array([0, 0, 4, 8])   # Coordonnées des sommets dans x2
plt.fill(x, y, 'gray', alpha=0.3)

# Tracer la fonction objective (pour Z = 80)
z_value = 80
plt.plot(x1, (z_value - c1 * x1) / c2, 'r--', label=r'$10x_1 + 20x_2 = 80$')

# Affichage de la légende et du titre
plt.legend(loc='upper right')
plt.title("Résolution Graphique pour la Maximisation de Z")

plt.grid(True)
plt.show()

# Résoudre avec scipy pour confirmation
res = linprog(c=[-c1, -c2], A_ub=A, b_ub=b, bounds=(0, None))
print("Solution optimale par linprog:", res.x)
print("Valeur optimale de Z:", -res.fun)
