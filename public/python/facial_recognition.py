import sys
import json
import face_recognition
import face_recognition_models

# Print the Python path and the location of face_recognition_models
print(sys.path)
print(face_recognition_models.__file__)

def main():
    if len(sys.argv) != 3:
        print("Usage: facial_recognition.py <known_images_file> <image_to_verify>")
        sys.exit(1)

    known_images_file = sys.argv[1]
    image_to_verify = sys.argv[2]

    # Read the known images from the file
    with open(known_images_file, 'r') as f:
        known_image_paths = f.read().splitlines()

    # Load known images
    known_images = [face_recognition.load_image_file(img) for img in known_image_paths]

    # Load the image to verify
    image_to_verify = face_recognition.load_image_file(image_to_verify)

    # Perform face recognition processing here
    # For example, output some results (this should be modified as needed)
    print(json.dumps({"status": "success", "message": "Face recognition completed."}))

if __name__ == "__main__":
    main()
