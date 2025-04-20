import sys
import matplotlib.pyplot as plt

# Read arguments
pressure1, temperature1, wind1 = float(sys.argv[1]), float(sys.argv[2]), float(sys.argv[3])
pressure2, temperature2, wind2 = float(sys.argv[4]), float(sys.argv[5]), float(sys.argv[6])

# Labels and values
labels = ['Pressure (Pa)', 'Temperature (°C)', 'Wind Speed (m/s)']
planet1 = [pressure1, temperature1, wind1]
planet2 = [pressure2, temperature2, wind2]
x = range(len(labels))

# Plotting
plt.figure(figsize=(10, 6))
plt.bar(x, planet1, width=0.4, label='Planet 1', align='center')
plt.bar([i + 0.4 for i in x], planet2, width=0.4, label='Planet 2', align='center')

plt.xticks([i + 0.2 for i in x], labels)
plt.ylabel("Values")
plt.title("Planet 1 vs Planet 2 Weather Comparison")
plt.legend()
plt.tight_layout()
plt.savefig("Comparison_images/user_upload_plot.png")
plt.show()
